<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V3\AuthController;

Route::post('register', [AuthController::class, 'register'])->name('v3.auth.register');
Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('v3.auth.verify-otp');
Route::post('change-phone', [AuthController::class, 'changePhone'])->name('v3.auth.change-phone')->middleware('auth:api');
