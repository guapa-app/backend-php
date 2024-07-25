<?php

use App\Http\Controllers\Api\V3\FavoriteController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/',                                         [FavoriteController::class, 'index']);
	Route::post('/',                                        [FavoriteController::class, 'create']);
	Route::delete('/{type}/{id}',                           [FavoriteController::class, 'delete']);
});
