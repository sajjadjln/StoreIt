<?php

namespace App\Services;

use App\DTOs\AuthResponse;
use App\DTOs\LoginCredentials;
use App\DTOs\SignupCredentials;
use App\Models\User;
use App\Contracts\AuthContract;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthContract
{
    public function signup(SignupCredentials $validatedData): AuthResponse
    {
        $user = User::create([
            'username' => $validatedData->username,
            'email' => $validatedData->email,
            'password' => $validatedData->password,
            'used_bytes' => 0,
            'quota_bytes' => $validatedData->quota_bytes ?? config('app.storage.min_quota'),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return new AuthResponse($user, $token);
    }

    public function login(LoginCredentials $credentials): AuthResponse
    {
        if (
            !Auth::attempt([
                'email' => $credentials->email,
                'password' => $credentials->password
            ])
        ) {
            throw new Exception('invalid email or password');
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return new AuthResponse($user, $token);
    }
}