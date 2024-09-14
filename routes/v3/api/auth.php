<?php

use App\Http\Controllers\Api\V3\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register'])->name('auth.register');
Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp');
Route::post('change-phone', [AuthController::class, 'changePhone'])->name('auth.change-phone')->middleware('auth:api');
