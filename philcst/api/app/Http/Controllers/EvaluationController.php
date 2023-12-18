<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{
    User,
    Questionnaire,
    AcademicYear,
    EvaluationEntry
};

class EvaluationController extends Controller
{
    public function fetchFaculties (Request $request) {
        $activeYear = AcademicYear::where('system_default', 'yes')->first();
        $questionnaire = Questionnaire::where('slug', $request->questionnaire_type)->first();

        $validator = Validator::make($request->all(), [
        'role' => 'required|exists:roles,role',
        'department_id' => 'required_if:role,faculty,dean,student|nullable|exists:departments,id'
        ], [
            'department_id.required_if' => "The department id field is required."
        ]);

        if ($validator->fails()) {
        return response([
            'errors' => $validator->errors()->all()
        ], 400);
        }

        $faculties = User::where('id', '<>', $request->evaluator_id)
            ->whereHas('role', function ($query) use ($request) {
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

        $evaluatedFaculties = null;
        if (isset($request->evaluator_id)) {
            $evaluatedFaculties = EvaluationEntry::where('academic_year_id', $activeYear->id)
                ->where('evaluator_id', $request->evaluator_id)
                ->where('questionnaire_id', $questionnaire->id)
                ->where('isDone', 1)
                ->get();
        }

        $peerEvaluatedFaculties = null;
        if ($request->questionnaire_type === 'peer-to-peer') {
            $peerEvaluatedFaculties = EvaluationEntry::where('academic_year_id', $activeYear->id)
                ->where('questionnaire_id', $questionnaire->id)
                ->where('isDone', 1)
                ->get()
                ->unique('evaluatee_id');
        }
            
        return response([
            'records' => $faculties,
            'evaluated_faculties' => $evaluatedFaculties,
            'peer_evaluated_faculties' => $peerEvaluatedFaculties
        ]);
    }

    public function fetchQuestionnaires () {
        $questionnaires = Questionnaire::all();
        return response([
            'records' => $questionnaires
        ], 200);
    }

    public function fetchActiveYear () {
        $year = AcademicYear::where('system_default', 'yes')->first();

        return response([
            'record' => $year
        ], 200);
    }

    public function updateComment (Request $request) {
        $entry = EvaluationEntry::where('id', $request->entry_id)->first();
        if (isset($entry)) {
            $entry->update([
                'comments' => $request->comments,
                'points' => $request->points,
            ]);
        }
        return response([
            'record' => $entry
        ], 200);
    }

    public function updateStatus (Request $request) {
        $entry = EvaluationEntry::where('id', $request->entry_id)->first();
        if (isset($entry)) {
            $entry->update([
                'isDone' => 1
            ]);
        }
        return response([
            'record' => $entry
        ], 200);
    }
}
