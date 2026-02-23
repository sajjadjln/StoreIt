<?php

namespace App\DTOs;

use App\Http\Resources\UserResource;
use App\Models\User;
class AuthResponse
{
    public function __construct(
        public readonly User $user,
        public readonly string $token
    ) {
    }

    public function toJsonResponse(string $message = 'Success', int $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        return (new UserResource($this->user))
            ->additional([
                'token' => $this->token,
                'success' => true,
                'message' => $message
            ])
            ->response()
            ->setStatusCode($statusCode);
    }
}
