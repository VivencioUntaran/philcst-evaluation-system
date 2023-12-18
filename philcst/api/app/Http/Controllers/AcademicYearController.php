<?php

namespace App\Http\Controllers;


use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Validator
};

class AcademicYearController extends Controller
{
    // academic year 
    public function index () {
        $academicYear = AcademicYear::orderBy('id', 'desc')
            ->with([
            'evaluationEntries' => function ($query) {
                $query->select('id', 'academic_year_id');
            }
        ])->get();
        return response([
            'records' => $academicYear
        ], 200);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'year'=> 'required',
            'semester'=> 'required',
            'status' => 'required|in:open,closed',
            'system_default' => 'required|in:yes,no',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $exist = $this->checkRecordExistence($request);
        if (isset($exist)) {
            return response([
                'errors' => 'Already exist'
            ], 400);
        }

        if ($request->status === 'open') {
            $this->closeAllStatus();
        }

        if ($request->system_default === 'yes') {
            $this->resetDefaults();
            $this->closeAllStatus();
        }

        $academicYear = AcademicYear::create([
            'year'=> $request->year,
            'semester' => $request->semester,
            'status' => $request->status,
            'system_default' => $request->system_default,
        ]);

        return response([
            'record' => $academicYear
        ], 200);

    }

    public function show (AcademicYear $academicYear) {
        return response([
            'record'=> $academicYear
        ], 200);
        
    }

    public function update(Request $request, AcademicYear $academicYear) {
        $validator = Validator::make($request->all(), [
            'system_default' => 'required|in:yes,no',
            'status' => 'required|in:open,closed'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        if ($request->status === 'open') {
            $this->closeAllStatus();
        }

        if ($request->system_default === 'yes') {
            $this->resetDefaults();
            $this->closeAllStatus();
        }

        $updatedAcademicYear = AcademicYear::find($academicYear->id);
        $updatedAcademicYear->update([
            'status' => $request->status,
            'system_default' => $request->system_default
        ]);

        return response([
            'record' => $academicYear,
            'payload' => $request->all(),
            'system_yes' => $request->system_default === 'yes',
            'status_open' => $request->status === 'open'
        ], 200);
        
    }

    public function destroy(AcademicYear $academicYear) {
        $academicYear->delete();

        return response([
            'record'=> $academicYear
        ], 200);
    }

    public function searchAcademicYear (Request $request) {
        $academicYear = AcademicYear::when(isset($request->keyword), function ($query) use ($request) {
            $query->where('year', 'like', '%' . $request->keyword . '%');
        })
            ->with([
            'evaluationEntries' => function ($query) {
                $query->select('id', 'academic_year_id');
            }
        ])->get();
        return response([
            'records' => $academicYear
        ], 200);
    }

    private function closeAllStatus () {
        $academicYears = AcademicYear::all();
        foreach ($academicYears as $key => $year) {
            $year->update([
                'status' => 'closed'
            ]);
        }
    }

    private function resetDefaults () {
        $academicYears = AcademicYear::all();
        foreach ($academicYears as $key => $year) {
            $year->update([
                'system_default' => 'no'
            ]);
        }
    }

    private function checkRecordExistence ($request) {
        $academicYear = AcademicYear::where('year', $request->year)
            ->where('semester', $request->semester)
            ->first();

        return $academicYear;
    } 
    // check record existence.
}
