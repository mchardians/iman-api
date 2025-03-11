<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_code' => 'required|string|max:36|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'gender' => 'required|in:laki-laki,perempuan',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'user_code' => $request->user_code,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'password' => bcrypt($request->password),
            'role_id' => 4,
        ]);

        if ($user) {
            return response()->json([
                'message' => 'User created successfully',
                'user' => $user,
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to create user',
        ], 400);
    }
}
