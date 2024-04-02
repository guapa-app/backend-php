<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OTPController;
use Illuminate\Support\Facades\Route;

Route::post('register',                                     [AuthController::class, 'register'])->name('auth.register');
Route::post('login',                                        [AuthController::class, 'login'])->name('auth.login');
Route::post('refresh_token',                                [AuthController::class, 'refreshToken'])->name('auth.refresh');
Route::post('send-otp',                                     [OTPController::class, 'sendOtp'])->name('auth.send-otp');
Route::post('verify-otp',                                   [OTPController::class, 'verifyOtp'])->name('auth.verify-otp');
Route::post('verify',                                       [OTPController::class, 'verify'])->name('auth.verify');
Route::post('check-phone',                                  [AuthController::class, 'checkIfPhoneExist'])->name('auth.check-phone');

Route::group(['middleware' => 'auth:api'], function () {
    Route::delete('logout',                                 [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('user',                                      [AuthController::class, 'user']);
    Route::delete('delete',                                 [AuthController::class, 'deleteAccount'])->name('auth.delete');
});
