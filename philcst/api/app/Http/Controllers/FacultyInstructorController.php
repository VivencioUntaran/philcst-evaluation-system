<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FacultyInstructorController extends Controller
{
    public function fetchFaculties (Request $request) {
        $records = User::whereHas('role', function ($query) use ($request) {
            $query->where('role', $request->role);
        })
            ->when(isset($request->department_id), function ($query) use ($request) {
                $query->where('department_id', $request->department_id);
            })
            ->when(isset($request->search_query), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search_query . '%');
            })
            ->with([
                'department'
            ])
            ->get();

        return response([
            'records' => $records
        ], 200);
    }
}
