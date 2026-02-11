<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {

        $validatedData = $request->validated();

        $user = User::create([
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'used_bytes' => 0,
            'quota_bytes' => $validatedData['quota_bytes'] ?? 1073741824,
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return (new UserResource($user))
            ->additional([
                'token' => $token,
                'success' => true,
                'message' => 'User registered successfully',
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'invalid email or password',
                'success' => false
            ], 401);
        }
        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return (new UserResource($user))
            ->additional([
                'token' => $token,
                "success" => true,
                "message" => "login completed successfully"
            ])
            ->response()
            ->setStatusCode(200);
    }
}
