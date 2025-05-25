<?php

use App\Http\Controllers\Api\Vendor\V3_1\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('send-otp', [AuthController::class, 'sendOtp']);

Route::post('refresh_token', [AuthController::class, 'refreshToken']);
Route::post('check-phone', [AuthController::class, 'checkIfPhoneExist']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user', [AuthController::class, 'userVendor']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::delete('delete', [AuthController::class, 'deleteAccount']);
});
