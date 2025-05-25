<?php

use App\Http\Controllers\Api\V2\AuthController;
use App\Http\Controllers\Api\V2\OTPController;
use Illuminate\Support\Facades\Route;

Route::post('register',                                     [AuthController::class, 'register']);
Route::post('login',                                        [AuthController::class, 'login']);
Route::post('refresh_token',                                [AuthController::class, 'refreshToken']);
Route::post('send-otp',                                     [OTPController::class, 'sendOtp']);
Route::post('verify-otp',                                   [OTPController::class, 'verifyOtp']);
Route::post('verify',                                       [OTPController::class, 'verify']);
Route::post('check-phone',                                  [AuthController::class, 'checkIfPhoneExist']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::delete('logout',                                 [AuthController::class, 'logout']);
    Route::get('user',                                      [AuthController::class, 'user']);
    Route::delete('delete',                                 [AuthController::class, 'deleteAccount']);
});
