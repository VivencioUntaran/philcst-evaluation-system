<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CriteriaPoint;
use Illuminate\Support\Facades\{
    Validator
};

class CriteriaPointController extends Controller
{
    public function index () {
        $criteriaPoints = CriteriaPoint::all();
        return response([
            'records' => $criteriaPoints
        ], 200);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'criteria_id' => 'required|exists:criterias,id',
            'label' => 'required',
            'point'  => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $criteriaPoint = CriteriaPoint::create([
            'criteria_id' => $request->criteria_id,
            'label' => $request->label,
            'point' => $request->point
        ]);

        return response([
            'record' => $criteriaPoint
        ], 200);
    }

    public function show (CriteriaPoint $criteriaPoint) {
        return response([
            'record' => $criteriaPoint
        ], 200);
    }

    public function update (Request $request, CriteriaPoint $criteriaPoint) {
        $validator = Validator::make($request->all(), [
            'criteria_id' => 'required|exists:criterias,id',
            'label' => 'required',
            'point'  => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $criteriaPoint->update([
            'criteria_id' => $request->criteria_id,
            'label' => $request->label,
            'point' => $request->point
        ]);

        return response([
            'record' => $criteriaPoint
        ], 200);
    }

    public function destroy (CriteriaPoint $criteriaPoint) {
        if (!$criteriaPoint) {
            return response([
                'errors' => ['Record not found']
            ], 404);
        }
        $criteriaPoint->delete();

        return response([
            'record' => $criteriaPoint
        ], 200);
    }
}
