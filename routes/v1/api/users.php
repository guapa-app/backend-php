<?php

use App\Http\Controllers\Api\UserController as ApiUserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/{id}',                                         [ApiUserController::class,  'single']);
	Route::match(['put', 'patch', 'post'], '/{id}',    [ApiUserController::class,  'update'])->name('users.update');
});
