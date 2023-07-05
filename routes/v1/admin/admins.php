<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'AdminController@index');
Route::get('/{id}', 'AdminController@admin');
Route::post('/', 'AdminController@create');
Route::put('/{id}', 'AdminController@update');
Route::delete('/{id}', 'AdminController@delete');
