<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/main_stats/{id}', 'DashboardController@main_stats');
});
