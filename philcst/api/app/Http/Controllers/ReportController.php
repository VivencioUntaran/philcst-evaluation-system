<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Questionnaire,
    User,
    EvaluationEntry
};

class ReportController extends Controller
{
    public function getEvaluationReport (Request $request) {
        $evaluatee = User::where('id', $request->evaluatee_id)
            ->with([
                'role',
                'department'
            ])
            ->first();
        
        $questionnaire = Questionnaire::where('id', $request->questionnaire_id)
            ->whereHas('evaluationEntries', function ($query) use ($request) {
                $query->where('evaluatee_id', $request->evaluatee_id)
                    ->where('academic_year_id', $request->academic_year_id)
                    ->where('isDone', 1);
            })
            ->with([
                'evaluationEntries' => function ($query) use ($request) {
                    $query->where('comments', '<>', '', 'and')
                        ->where('evaluatee_id', $request->evaluatee_id)
                        ->where('academic_year_id', $request->academic_year_id)
                        ->with([
                            'evaluator' => function ($query) {
                                $query->with('role', 'department');
                            }
                        ]);
                },
                'criterias' => function ($query) use ($request) {
                    $query->where('questionnaire_id', $request->questionnaire_id)
                        ->with([
                            'questions' => function ($query) use ($request) {
                                $query->with([
                                    'evaluationAnswers' => function ($query) use ($request) {
                                        $query->whereHas('evaluationEntry', function ($query) use ($request) {
                                            $query->where('academic_year_id', $request->academic_year_id)
                                            ->where('evaluatee_id', $request->evaluatee_id);
                                        })
                                        ->with([
                                            'choice' => function ($query) {
                                                $query->with(['criteriaPoint']);
                                            }
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                }
            ])
            ->first();
        
        
        $formattedCriterias = $this->formatCriterias($questionnaire);
        $totalPoints = $this->calculateTotalPoints($formattedCriterias);

        $comments = $this->getEvaluationComments($questionnaire);
        
        $peerPoints = $this->getPeerPoints($questionnaire);
        $returnRecord = (object) [
            'slug' => $questionnaire?->slug,
            'criterias' => $formattedCriterias,
            'total_points' => $totalPoints + $peerPoints,
            'evaluatee' => $evaluatee,
            'comments' => $comments,
            'peerPoints' => $peerPoints,
        ];
        return response([
            'record' => $returnRecord
        ]);
    }

    private function formatCriterias ($questionnaire) {
        $returnCriterias = [];
        
        if (!isset($questionnaire->criterias)) {
            return [];
        }
        foreach($questionnaire->criterias as $key => $criteria) {
            $criteriaObj = (object) [
                'criteria' => $criteria,
                'average' => $this->computeCriteriaAverage($criteria)
            ];
            array_push($returnCriterias, $criteriaObj);
        }

        return $returnCriterias;
    }

    private function computeCriteriaAverage ($criteria) {
        $averagePerCriteria = 0;
        $totalPerCriteria = 0;

        $criteriaQuestions = $criteria->questions;
        foreach($criteriaQuestions as $key => $question) {
            $totalPerQuestion = 0;

            foreach($question->evaluationAnswers as $key => $answer) {
                $point = $answer->choice->criteriaPoint->point;
                $totalPerQuestion += $point;
            }
            $totalPerCriteria += $totalPerQuestion;
        }
        $averagePerCriteria = $totalPerCriteria / count($criteria->questions);

        return round($averagePerCriteria, 2);
    }

    private function calculateTotalPoints ($formattedCriterias) {
        $totalPoints = 0;
        foreach($formattedCriterias as $key => $criteria) {
            $totalPoints += $criteria->average;
        }
        return round($totalPoints, 2);
    }

    private function getEvaluationComments ($questionnaire) {
        
        $evaluationEntries = $questionnaire?->evaluationEntries;

        return $evaluationEntries;
    }


    public function fetchFaculties (Request $request) {
        $faculties = User::whereHas('role', function ($query) {
            $query->whereNotIn('role', ['student', 'admin']);
        })
            ->when(isset($request->department_id), function ($query) use ($request) {
                $query->where('department_id', $request->department_id);
            })
            ->when(isset($request->evaluatee_name), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->evaluatee_name . '%');
            })
            ->when(isset($request->evaluatee_role), function ($query) use ($request) {
                $query->whereHas('role', function ($query) use ($request) {
                    $query->where('role', $request->evaluatee_role);
                });
            })
            ->with([
                'department', 
                'role'
            ])
            ->get();

        return response([
            'records' => $faculties
        ],  200);
    }

    private function getPeerPoints ($questionnaire) {
        $average = 0;
        $additionalPoints = 0;
        $pointArr = [];
        $slug = $questionnaire?->slug;

        if ($slug === 'peer-to-peer') {

            $entries = $questionnaire?->evaluationEntries;
            if ($entries) {
                foreach($entries as $key => $entry) {
                    if ($entry->points !== 0) {
                        $additionalPoints += $entry->points;
                        array_push($pointArr, $entry->points);
                    }
                }
            }
        }

        if (count($pointArr)) {
            $average = $additionalPoints / count($pointArr);
        }
        return $average;
    }
}