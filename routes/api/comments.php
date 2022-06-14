<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'CommentController@index');
Route::get('/{id}', 'CommentController@single');

Route::group(['middleware' => 'auth:api'], function() {
	Route::post('/', 'CommentController@create');
	Route::match(['put', 'patch', 'post'], '/{id}', 'CommentController@update');
	Route::delete('/{id}', 'CommentController@delete');
});
