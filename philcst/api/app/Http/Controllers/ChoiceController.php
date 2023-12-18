<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Validator
};

use App\Models\Choice;

class ChoiceController extends Controller
{
    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|exists:questions,id',
            'criteria_point_id' => 'required|exists:criteria_points,id',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $choice = Choice::create([
            'question_id' => $request->question_id,
            'criteria_point_id' => $request->criteria_point_id
        ]);

        return response([
            'record' => $choice
        ], 200);
    }

    public function destroy (Choice $choice) {
        $choice->delete();
        return response([
            'record' => $choice
        ], 200);
    }
}
