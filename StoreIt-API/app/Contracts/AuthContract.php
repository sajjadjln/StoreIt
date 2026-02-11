<?php

namespace App\Contracts;
interface AuthContract
{
    public function signup(array $validatedData): array;
    public function login(array $credentials): array;
}