<?php

namespace App\Services;

use App\DTOs\AuthResponse;
use App\Models\User;
use App\Contracts\AuthContract;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthContract
{
    public function signup(array $validatedData): AuthResponse
    {
        $user = User::create([
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'used_bytes' => 0,
            'quota_bytes' => $validatedData['quota_bytes'] ?? config('app.storage.min_quota'),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return new AuthResponse($user, $token);
    }

    public function login(array $credentials): AuthResponse
    {
        if (!Auth::attempt($credentials)) {
            throw new Exception('invalid email or password');
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return new AuthResponse($user, $token);
    }
}