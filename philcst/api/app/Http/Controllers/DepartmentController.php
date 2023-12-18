<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Validator
};
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index () {
        $departments = Department::all();
        return response([
            'records' => $departments
        ], 200);
    }


    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'department'=> 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $department = Department::create([
            'department' => $request->department,
        ]);

        return response([
            'record' => $department
        ], 200);
    }
}
