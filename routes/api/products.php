<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'ProductController@index');
Route::get('/{id}', 'ProductController@single');

Route::group(['middleware' => 'auth:api'], function() {
	Route::post('/', 'ProductController@create');
	Route::match(['put', 'patch', 'post'], '/{id}', 'ProductController@update');
	Route::delete('/{id}', 'ProductController@delete');
});
