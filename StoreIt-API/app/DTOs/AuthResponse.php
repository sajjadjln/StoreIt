<?php

namespace App\DTOs;

use App\Models\User;
class AuthResponse
{
    public function __construct(
        public readonly User $user,
        public readonly string $token
    ) {
    }
}
