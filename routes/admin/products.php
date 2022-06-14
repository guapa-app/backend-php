<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/', 'ProductController@index');
	Route::get('/{id}', 'ProductController@single');
	Route::post('/', 'ProductController@create');
    Route::put('/{id}', 'ProductController@update');
    Route::delete('/{id}', 'ProductController@delete');
});
