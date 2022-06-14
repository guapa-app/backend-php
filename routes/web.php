<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/navigation', function () {
    return view('welcome');
})->name('navigation');

// Load the app for all requests
Route::any('{any?}', function () {
    return view(config('cosmo.home_view')); // Admin view
})->where('any', '^((?!api|admin).)*$');

// Load the admin app for /admin requests
Route::any('admin/{any?}', function () {
    return view(config('cosmo.admin_view')); // Admin view
})->where('any', '^((?!api).)*$');
