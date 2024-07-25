<?php

use App\Http\Controllers\Api\V3\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register',         [AuthController::class, 'register'])->name('auth.register');
Route::post('check-phone',      [AuthController::class, 'checkIfPhoneExist'])->name('auth.check-phone');
Route::post('verify-otp',       [AuthController::class, 'verifyOtp'])->name('auth.verify-otp');
