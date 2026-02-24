<?php

namespace App\Responses;

use App\DTOs\AuthResponse;
use App\Http\Resources\UserResource;

class AuthResponseBuilder
{
    public function success(AuthResponse $auth, string $message, int $status = 200)
    {
        return (new UserResource($auth->user))
            ->additional([
                'token' => $auth->token,
                'success' => true,
                'message' => $message
            ])
            ->response()
            ->setStatusCode($status);
    }
}
