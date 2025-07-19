<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V2\OTPController;
use App\Http\Controllers\Api\V2\AuthController;

Route::post('register', [AuthController::class, 'register'])->name('v2.auth.register');
Route::post('login',[AuthController::class, 'login'])->name('v2.auth.login');
Route::post('refresh_token',[AuthController::class, 'refreshToken'])->name('v2.auth.refresh');
Route::post('send-otp', [OTPController::class, 'sendOtp'])->name('v2.auth.send-otp');
Route::post('verify-otp',   [OTPController::class, 'verifyOtp'])->name('v2.auth.verify-otp');
Route::post('verify',   [OTPController::class, 'verify'])->name('v2.auth.verify');
Route::post('check-phone',  [AuthController::class, 'checkIfPhoneExist'])->name('v2.auth.check-phone');

Route::group(['middleware' => 'auth:api'], function () {
    Route::delete('logout', [AuthController::class, 'logout'])->name('v2.auth.logout');
    Route::get('user',  [AuthController::class, 'user']);
    Route::delete('delete', [AuthController::class, 'deleteAccount'])->name('v2.auth.delete');
});
