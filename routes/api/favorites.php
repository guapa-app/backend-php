<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/', 'FavoriteController@index');
	Route::post('/', 'FavoriteController@create');
	Route::delete('/{type}/{id}', 'FavoriteController@delete');
});
