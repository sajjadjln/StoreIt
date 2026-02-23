<?php

namespace App\DTOs;

class LoginCredentials
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}