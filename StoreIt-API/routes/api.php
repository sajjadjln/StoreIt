<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:5,1'])->group(function () {
    Route::post('/auth/signup', [AuthController::class, 'signup']);
    Route::post('/auth/login', [AuthController::class, 'login']);
});
Route::get("/user/profile", [UserController::class, 'profile'])->middleware('auth:sanctum');