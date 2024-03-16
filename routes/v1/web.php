<?php

use App\Http\Controllers\Api\V2\OrderController;
use App\Http\Controllers\RegistrationController;
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

Route::get('/.well-known/apple-developer-merchantid-domain-association', function () {
    return view('apple_pay');
});
Route::get('/{id}/show-invoice', [OrderController::class, 'showInvoice']);

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/register', [RegistrationController::class, 'registerForm'])->name('register.form');
Route::post('/register', [RegistrationController::class, 'register'])->name('register')->middleware('throttle:10');

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
