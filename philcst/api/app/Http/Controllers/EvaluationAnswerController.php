<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluationAnswer;
use Illuminate\Support\Facades\Validator;

class EvaluationAnswerController extends Controller
{
    public function index (Request $request) {
      $evaluationAnswer = EvaluationAnswer::all();
      return response([
          'records' => $evaluationAnswer
      ], 200);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
          'evaluation_entry_id' => 'required|exists:evaluation_entries,id',
          'criteria_id' => 'required|exists:criterias,id',
          'question_id' => 'required|exists:questions,id',
          'choice_id' => 'required|exists:choices,id',
        ]);

        if ($validator->fails()) {
          return response([
              'errors' => $validator->errors()->all()
          ], 400);
        }

        $this->deleteCreatedChoice($request);

        $evaluationAnswer = EvaluationAnswer::create([
          'evaluation_entry_id'=> $request->evaluation_entry_id,
          'criteria_id'=> $request->criteria_id,
          'question_id' => $request->question_id,
          'choice_id' => $request->choice_id,
        ]);

        return response([
          'record' => $evaluationAnswer
        ], 200);
    }

    private function deleteCreatedChoice ($request) {
      $records = EvaluationAnswer::where('evaluation_entry_id', $request->evaluation_entry_id)
        ->where('criteria_id', $request->criteria_id)
        ->where('question_id', $request->question_id)
        ->get();

      if (count($records)) {
        foreach($records as $key => $record) {
          $record->delete();
        }
      }
    }
}
