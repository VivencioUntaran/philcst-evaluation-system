<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Questionnaire;
use Illuminate\Support\Facades\{
    Validator
};

class QuestionnaireController extends Controller
{
    public function index () {
        $questionnaires = Questionnaire::all();
        return response([
            'records' => $questionnaires
        ], 200);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:questionnaires',
            'slug'  => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $questionnaire = Questionnaire::create([
            'name' => $request->name,
            'slug' => $request->slug
        ]);

        return response([
            'record' => $questionnaire
        ], 200);
    }

    public function show ($id) {
        $questionnaire = Questionnaire::where('id', $id)
            ->with(['criterias'])
            ->first();
        return response([
            'record' => $questionnaire
        ], 200);
    }

    public function update (Request $request, Questionnaire $questionnaire) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:questionnaires',
            'slug'  => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $questionnaire->update([
            'name' => $request->name,
            'slug' => $request->slug
        ]);

        return response([
            'record' => $questionnaire
        ], 200);
    }

    public function destroy (Questionnaire $questionnaire) {
        if (!$questionnaire) {
            return response([
                'errors' => ['Record not found']
            ], 404);
        }
        $questionnaire->delete();

        return response([
            'record' => $questionnaire
        ], 200);
    }

    public function getQuestionnaireBySlug ($slug) {
        $questionnaire = Questionnaire::where('slug', $slug)
            ->with('criterias')
            ->first();

        return response([
            'record' => $questionnaire
        ]);
    }
    //getQuestionnaireBySlug
}
