<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller 
{

    public function register(RegisterRequest $request)
    {
        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('TodoApp')->plainTextToken;

            return successResponse('Successfully registered.', [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ], 201);

        } catch (\Throwable $th) {
            logExceptionDetail($th);
            return errorResponse($th->getMessage(), 500);
        }
    }


    public function login(LoginRequest $request)
    {
        try {

            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            if (!auth()->attempt($credentials)) {
                return errorResponse('Invalid credentials', 401);
            }

            $token = auth()->user()->createToken('TodoAppToken')->plainTextToken;

            return successResponse('Successfully logined.', [
                'token' => $token,
                'user' => auth()->user()
            ], 200);

        } catch (\Throwable $th) {
            return errorResponse($th->getMessage(), 500);
        }
    }


    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return successResponse('Logged out successfully');
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage(), 500);
        }
    }
}
