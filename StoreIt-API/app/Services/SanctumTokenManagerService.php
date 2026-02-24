<?php


namespace App\Services;

use App\Contracts\TokenManagerContract;
use App\Models\User;

class SanctumTokenManagerService implements TokenManagerContract
{
    public function generate(User $user): string
    {
        return $user->createToken(TokenName::API->value)->plainTextToken;
    }
}

enum TokenName: string
{
    case API = 'api_token';
    case WEB = 'web_token';
}