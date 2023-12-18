<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{
    Criteria,
    CriteriaPoint
};
use Illuminate\Support\Facades\{
    Validator
};
use Illuminate\Support\Str;

class CriteriaController extends Controller
{
    public function index (Request $request) {
        $categories = Criteria::all();
        return response([
            'records' => $categories
        ], 200);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'name' => 'required|unique:questionnaires'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $criteria = Criteria::create([
            'questionnaire_id' => $request->questionnaire_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        foreach($request->points as $key => $point) {
            $criteriaPoint = CriteriaPoint::create([
                'criteria_id' => $criteria->id,
                'label' => $point['value'],
                'point' => $point['value']
            ]);
        }

        return response([
            'record' => $criteria
        ], 200);
    }

    public function show ($id) {
        $criteria = Criteria::where('id', $id)
            ->with(['criteriaPoints', 'questions'])
            ->first();

        return response([
            'record' => $criteria
        ], 200);
    }

    public function update (Request $request, Criteria $criteria) {
        $validator = Validator::make($request->all(), [
            'questionnaire_id' => 'required|exists:questionnaires,id',
            'name' => 'required|unique:questionnaires'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $criteria->update([
            'questionnaire_id' => $request->questionnaire_id,
            'name' => $request->name,
            'slug' => $request->slug
        ]);

        return response([
            'record' => $criteria
        ], 200);
    }

    public function destroy (Criteria $criteria) {
        $criteria->delete();

        return response([
            'record' => $criteria
        ], 200);
    }
}
