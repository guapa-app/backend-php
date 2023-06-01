<?php

use App\Http\Controllers\Api\ProductController as ApiProductController;
use Illuminate\Support\Facades\Route;

Route::get('/',                                              [ApiProductController::class, 'index']);
Route::get('/{id}',                                          [ApiProductController::class, 'single']);

Route::group(['middleware' => 'auth:api'], function() {
	Route::post('/',                                         [ApiProductController::class, 'create']);
	Route::match(['put', 'patch', 'post'], '/{id}', [ApiProductController::class, 'update']);
	Route::delete('/{id}',                                   [ApiProductController::class, 'delete']);
});
