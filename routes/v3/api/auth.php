<?php

use App\Http\Controllers\Api\V3\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('change-phone', [AuthController::class, 'changePhone']);
