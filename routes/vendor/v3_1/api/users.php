<?php

use App\Http\Controllers\Api\Vendor\V3_1\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/{id}', [UserController::class, 'single']);
    Route::match(['put', 'patch', 'post'], '/{id}', [UserController::class, 'update'])->name('users.update');
});
