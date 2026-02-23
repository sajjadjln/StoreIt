<?php

namespace App\Contracts;

use App\DTOs\AuthResponse;
use App\DTOs\LoginCredentials;
use App\DTOs\SignupCredentials;
interface AuthContract
{
    public function signup(SignupCredentials $validatedData): AuthResponse;
    public function login(LoginCredentials $credentials): AuthResponse;
}