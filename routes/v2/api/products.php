<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V2\ProductController;

Route::get('/',                                             [ProductController::class, 'index'])->name('v2.products.index');
Route::get('/{id}',                                          [ProductController::class, 'single'])->name('v2.products.show');

Route::group(['middleware' => 'auth:api'], function() {
	Route::post('/',                                         [ProductController::class, 'create']);
	Route::match(['put', 'patch', 'post'], '/{id}', [ProductController::class, 'update']);
	Route::delete('/{id}',                                   [ProductController::class, 'delete']);
});
