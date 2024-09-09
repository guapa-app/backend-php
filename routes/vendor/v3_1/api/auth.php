<?php

use App\Http\Controllers\Api\Vendor\V3_1\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register',   [AuthController::class, 'register'])->name('auth.register');
Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp');
Route::get('user',        [AuthController::class, 'userVendor']);
