<?php

use App\Http\Controllers\Api\Vendor\V3_1\AuthController;
use App\Http\Controllers\Api\Vendor\V3_1\OTPController;
use Illuminate\Support\Facades\Route;

Route::post('register',   [AuthController::class, 'register'])->name('auth.register');
Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp');
Route::post('send-otp',   [OTPController::class, 'sendOtp'])->name('auth.send-otp');

Route::post('refresh_token',                                [AuthController::class, 'refreshToken'])->name('auth.refresh');
Route::post('check-phone',                                  [AuthController::class, 'checkIfPhoneExist'])->name('auth.check-phone');


Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user',        [AuthController::class, 'userVendor']);
    Route::post('logout',     [AuthController::class, 'logout'])->name('auth.logout');
    Route::delete('delete',   [AuthController::class, 'deleteAccount'])->name('auth.delete');
});
