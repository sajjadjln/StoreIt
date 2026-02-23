<?php

namespace App\Http\Controllers;

use App\DTOs\LoginCredentials;
use App\DTOs\SignupCredentials;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Contracts\AuthContract;
use App\Http\Resources\UserResource;
use Exception;

class AuthController extends Controller
{
    public function __construct(
        protected readonly AuthContract $authService
    ) {
    }


    public function signup(SignupRequest $request)
    {

        $validatedData = $request->validated();

        $credentials = new SignupCredentials(
            $validatedData['username'],
            $validatedData['email'],
            $validatedData['password'],
        );

        $result = $this->authService->signup($credentials);

        return (new UserResource($result->user))
            ->additional([
                'token' => $result->token,
                'success' => true,
                'message' => 'User registered successfully',
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function login(LoginRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $credentials = new LoginCredentials(
                $validatedData['email'],
                $validatedData['password'],
            );

            $result = $this->authService->login($credentials);

            return (new UserResource($result->user))
                ->additional([
                    'token' => $result->token,
                    "success" => true,
                    "message" => "login completed successfully"
                ])
                ->response()
                ->setStatusCode(200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false
            ], 401);

        }
    }
}
