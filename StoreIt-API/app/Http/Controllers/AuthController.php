<?php

namespace App\Http\Controllers;

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

        $user = $this->authService->signup($validatedData);

        return (new UserResource($user['user']))
            ->additional([
                'token' => $user['token'],
                'success' => true,
                'message' => 'User registered successfully',
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            $user = $this->authService->login($credentials);

            return (new UserResource($user['user']))
                ->additional([
                    'token' => $user['token'],
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
