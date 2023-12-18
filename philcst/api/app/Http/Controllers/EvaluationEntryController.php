<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{
    EvaluationEntry,
    AcademicYear
};

class EvaluationEntryController extends Controller
{
    public function index (Request $request) {
        $entries = EvaluationEntry::with([
            'evaluator',
            'evaluatee'
        ])
            ->get();
        return response([
            'records' => $entries
        ], 200);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'evaluator_id' => 'required|exists:users,id',
            'evaluatee_id' => 'required|exists:users,id',
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $existingEntry = $this->getEvaluationEntry($request);
        if ($existingEntry) {
            return response([
                'record' => $existingEntry,
                'error' => 'Entry exist'
            ], 401);
        }

        $entry = EvaluationEntry::create([
            'evaluator_id'=> $request->evaluator_id,
            'evaluatee_id'=> $request->evaluatee_id,
            'questionnaire_id' => $request->questionnaire_id,
            'academic_year_id' => $request->academic_year_id,
            'comments' => $request->input('comments', null),
        ]);

        return response([
            'record' => $entry
        ], 200);
    }

    public function entryExistenceChecker (Request $request) {
        $validator = Validator::make($request->all(), [
            'evaluator_id' => 'required|exists:users,id',
            'evaluatee_id' => 'required|exists:users,id',
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $evaluationEntry = $this->getEvaluationEntry($request);

        return response([
            'record' => $evaluationEntry
        ]);
    }

    public function singleEntry (Request $request) {
        $validator = Validator::make($request->all(), [
            'entry_id' => 'required|exists:evaluation_entries,id',
            'questionnaire' => 'required|exists:questionnaires,slug'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $id = $request->entry_id;
        $entry = EvaluationEntry::where('id', $id)
            ->with([
                'evaluator', 
                'evaluatee' => function ($query) {
                    $query->with([
                        'role',
                        'department'
                    ]);
                },
                'questionnaire' => function ($query) use ($id, $request) {
                    $query->where('slug', $request->questionnaire)
                        ->with([
                            'criterias' => function ($query) use ($id) {
                                $query->with([
                                    'questions' => function ($query) use ($id) {
                                        $query->with([
                                            'choices' => function ($query) use ($id) {
                                                $query->with([
                                                    'evaluationAnswer' => function ($query) use ($id) {
                                                        $query->where('evaluation_entry_id', $id);
                                                    },
                                                    'criteriaPoint'
                                                ]);
                                            }
                                        ]);
                                    },
                                    'criteriaPoints'
                                ]);
                            }
                    ]);
                }
            ])
            ->first();

        $academicYear = AcademicYear::where('system_default', 'yes')->first();

        return response([
            'record' => $entry,
            'academic_year' => $academicYear,
            'type' => $request->questionnaire
        ], 200);
    }
    
    private function getEvaluationEntry($request) {
        $evaluationEntry = EvaluationEntry::where([
            'evaluator_id' => $request->evaluator_id,
            'evaluatee_id' => $request->evaluatee_id,
            'questionnaire_id' => $request->questionnaire_id,
            'academic_year_id' => $request->academic_year_id
        ])
            ->first();
        return $evaluationEntry;

        // check
    }
}
