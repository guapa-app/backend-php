<?php

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use Illuminate\Support\Facades\Route;

Route::post('register',                                     [ApiAuthController::class, 'register'])->name('auth.register');
Route::post('login',                                        [ApiAuthController::class, 'login'])->name('auth.login');
Route::post('verify',                                       [ApiAuthController::class, 'verify'])->name('auth.verify');
Route::post('refresh_token',                                [ApiAuthController::class, 'refreshToken'])->name('auth.refresh');
Route::post('send-otp',                                     [ApiAuthController::class, 'sendSinchOtp'])->name('auth.send-otp');
Route::post('check-phone',                                  [ApiAuthController::class, 'checkIfPhoneExist'])->name('auth.check-phone');
Route::post('verify-otp',                                   [ApiAuthController::class, 'verifySinchOtp'])->name('auth.verify-otp');

Route::group(['middleware' => 'auth:api'], function () {
    Route::delete('logout',                                 [ApiAuthController::class, 'logout'])->name('auth.logout');
    Route::get('user',                                      [ApiAuthController::class, 'user']);
    Route::delete('delete',                                 [ApiAuthController::class, 'deleteAccount'])->name('auth.delete');
});
