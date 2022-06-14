<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/', 'PostController@index');
	Route::get('/{id}', 'PostController@single');
	Route::post('/', 'PostController@create');
    Route::match(['put', 'patch', 'post'], '/{id}', 'PostController@update');
    Route::delete('/{id}', 'PostController@delete');
});