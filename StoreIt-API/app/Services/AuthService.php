<?php

namespace App\Services;

use App\Models\User;
use App\Contracts\AuthContract;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthContract
{
    public function signup(array $validatedData): array
    {
        $user = User::create([
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'used_bytes' => 0,
            'quota_bytes' => $validatedData['quota_bytes'] ?? config('app.storage.min_quota'),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user
        ];
    }

    public function login(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            return [
                'success' => false,
            ];
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'success' => true,
            'user' => $user,
            'token' => $token
        ];
    }
}