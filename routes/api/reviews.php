<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/', 'ReviewController@index');
	Route::post('/', 'ReviewController@create');
});
