<?php

use App\Http\Controllers\Api\User\V3_1\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('refresh_token', [AuthController::class, 'refreshToken']);
Route::post('send-otp', [AuthController::class, 'sendOtp']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::delete('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    Route::delete('delete', [AuthController::class, 'deleteAccount']);
    Route::post('/change-phone', [AuthController::class, 'updatePhone']);
});
