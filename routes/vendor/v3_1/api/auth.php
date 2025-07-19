<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\V3_1\AuthController;

Route::post('register', [AuthController::class, 'register'])->name('v3_1.vendor.auth.register');
Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('v3_1.vendor.auth.verify-otp');
Route::post('send-otp', [AuthController::class, 'sendOtp'])->name('v3_1.vendor.auth.send-otp');

Route::post('refresh_token', [AuthController::class, 'refreshToken'])->name('v3_1.vendor.auth.refresh');
Route::post('check-phone', [AuthController::class, 'checkIfPhoneExist'])->name('v3_1.vendor.auth.check-phone');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user', [AuthController::class, 'userVendor']);
    Route::post('logout', [AuthController::class, 'logout'])->name('v3_1.vendor.auth.logout');
    Route::delete('delete', [AuthController::class, 'deleteAccount'])->name('v3_1.vendor.auth.delete');
});
