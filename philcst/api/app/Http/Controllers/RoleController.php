<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Validator
};
use App\Models\Role;

class RoleController extends Controller
{

    public function index () {
        $roles = Role::all();
        return response([
            'records' => $roles
        ], 200);
    }


    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required|exists:user,id',
            'role'=> 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $role = Role::create([
            // 'user_id' => $request->user_id,
            'role' => $request->role,
        ]);

        return response([
            'record' => $role
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
