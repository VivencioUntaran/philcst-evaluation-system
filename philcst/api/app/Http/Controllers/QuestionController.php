<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Validator
};
use App\Models\{
    Question,
    Criteria,
    Choice
};

class QuestionController extends Controller
{
    public function index () {
        $question = Question::all();
        return response([
            'records' => $question
        ], 200);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'criteria_id' => 'required|exists:criterias,id',
            'question' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $question = Question::create([
            'criteria_id' => $request->criteria_id,
            'question' => $request->question
        ]);

        $criteria = Criteria::where('id', $request->criteria_id)
            ->with(['criteriaPoints'])
            ->first();

        foreach($criteria->criteriaPoints as $key => $point) {
            $choice = Choice::create([
                'question_id' => $question->id,
                'criteria_point_id' => $point->id
            ]);
        }

        return response([
            'record' => $question
        ], 200);
    }

    public function show ($id) {
        $question = Question::where('id', $id)
            ->with(['choices' => function ($q) {
                $q->with(['criteriaPoint']);
            }])
            ->first();
        return response([
            'record' => $question
        ], 200);
    }

    public function update (Request $request, Question $question) {
        $validator = Validator::make($request->all(), [
            'criteria_id' => 'required|exists:criterias,id',
            'question' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $question->update([
            'criteria_id' => $request->criteria_id,
            'question' => $request->question
        ]);

        return response([
            'record' => $question
        ], 200);
    }

    public function destroy (Question $question) {
        if (!$question) {
            return response([
                'errors' => ['Record not found']
            ], 404);
        }
        $question->delete();

        return response([
            'record' => $question
        ], 200);
    }
}
