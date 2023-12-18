<?php

namespace App\Http\Controllers;

use App\Models\{User, Role};
use Illuminate\Http\{
    Request,
    Response
};
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function index () {
        $user = User::with(['role','department'])
            ->get();
        return response([
            'records' => $user,
        ], 200);
    }
    public function register (Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password'=> 'required|same:password_confirmation',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'sometimes',
            'student_number' => 'sometimes'
        ]);
        if (isset($request->readable_role) && $request->readable_role === 'student') {
            $fields = $request->validate([
                'student_number' => 'required|unique:users,student_number'
            ]);
        }
        if (isset($request->readable_role) && $request->readable_role === 'dean' || $request->readable_role === 'student') {
            $fields = $request->validate([
                'department_id' => 'sometimes|exists:departments,id',
            ]);
        }

        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> bcrypt($request->password),
            'role_id' => $request->role_id,
            'department_id'=> $request->department_id,
            'student_number' => $request->student_number,
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            // 'student_info' => $student_info
        ];

        return response($response, 201);
    }

    public function updateUser (Request $request) {
        $updatePassword = false;
        $user = User::where('id', $request->user_id)
            ->with(['role'])
            ->first();

        if (isset($request->password) && strlen($request->password)) {
            $updatePassword = true;
            $fields = $request->validate([
                'password' => 'required|same:password_confirmation',
            ]);
        }

        $user->update([
            'name'=> $request->name,
            'email'=> $request->email,
        ]);

        if ($updatePassword) {
            $user->update([
                'password'=> bcrypt($request->password)
            ]);
        }
        return response([
            'record' => $user,
        ]);
    }

    public function login (Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password'=> 'required|string',
        ]);

        // Check email
        $user = User::where('email', $fields['email'])
            ->with(['role'])
            ->first();

        //Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }

        
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token'=> $token
        ];

        return response($response, 201);
    }

    public function logout (Request $request) {
        auth()->user()->tokens()->delete();
 
        return [
            'message'=> 'Logged out'
        ];
    }

    public function destroy (User $user) {
        if (!$user) {
            return response([
                'errors' => ['Record not found']
            ], 404);
        }
        $user->delete();

        return response([
            'record' => $user
        ], 200);
    }

    public function filterRole (Request $request) {
        $role = Role::where('role', $request->role)->first();

        return response([
            'record' => $role
        ], 200);
    }
}
