<?php


namespace App\Services;

use App\Contracts\TokenManagerContract;
use App\Models\User;

class SanctumTokenManagerService implements TokenManagerContract
{
    public function generate(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}