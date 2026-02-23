<?php

namespace App\Http\Controllers;

use App\DTOs\LoginCredentials;
use App\DTOs\SignupCredentials;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Contracts\AuthContract;
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

        return $result->toJsonResponse('User registered successfully', 201);
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

            return $result->toJsonResponse('Login completed successfully');

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false
            ], 401);

        }
    }
}
