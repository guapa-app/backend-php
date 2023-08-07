<?php

use App\Http\Controllers\Api\V2\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register',                                     [AuthController::class, 'register'])->name('auth.register');
Route::post('login',                                        [AuthController::class, 'login'])->name('auth.login');
Route::post('verify',                                       [AuthController::class, 'verify'])->name('auth.verify');
Route::post('refresh_token',                                [AuthController::class, 'refreshToken'])->name('auth.refresh');
Route::post('send-otp',                                     [AuthController::class, 'sendSinchOtp'])->name('auth.send-otp');
Route::post('check-phone',                                  [AuthController::class, 'checkIfPhoneExist'])->name('auth.check-phone');
Route::post('verify-otp',                                   [AuthController::class, 'verifySinchOtp'])->name('auth.verify-otp');

Route::group(['middleware' => 'auth:api'], function () {
    Route::delete('logout',                                 [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('user',                                      [AuthController::class, 'user']);
    Route::delete('delete',                                 [AuthController::class, 'deleteAccount'])->name('auth.delete');
});
