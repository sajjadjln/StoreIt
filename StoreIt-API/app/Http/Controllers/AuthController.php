<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Contracts\AuthContract;
use App\Http\Resources\UserResource;

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
        $credentials = $request->validated();
        $user = $this->authService->login($credentials);

        if ($user['success'] == false) {
            return response()->json([
                'message' => 'invalid email or password',
                'success' => false
            ], 401);
        }

        return (new UserResource($user['user']))
            ->additional([
                'token' => $user['token'],
                "success" => true,
                "message" => "login completed successfully"
            ])
            ->response()
            ->setStatusCode(200);
    }
}
