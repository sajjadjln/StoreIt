<?php

namespace App\DTOs;

class SignupCredentials
{
    public function __construct(
        public readonly string $username,
        public readonly string $email,
        public readonly string $password,
        public readonly ?int $quota_bytes = null
    ) {
    }
}