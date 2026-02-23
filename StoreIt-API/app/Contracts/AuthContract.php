<?php

namespace App\Contracts;

use App\DTOs\AuthResponse;
interface AuthContract
{
    public function signup(array $validatedData): AuthResponse;
    public function login(array $credentials): AuthResponse;
}