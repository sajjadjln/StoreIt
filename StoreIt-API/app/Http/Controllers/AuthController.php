<?php

namespace App\Http\Controllers;

use App\DTOs\LoginCredentials;
use App\DTOs\SignupCredentials;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Contracts\AuthContract;
use App\Responses\AuthResponseBuilder;
use Exception;

class AuthController extends Controller
{
    public function __construct(
        protected readonly AuthContract $authService,
        protected AuthResponseBuilder $responseBuilder
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
        return $this->responseBuilder->success(
            $result,
            'User registered successfully',
            201
        );
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

            return $this->responseBuilder->success(
                $result,
                'Login completed successfully',
                200
            );

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false
            ], 401);

        }
    }
}
