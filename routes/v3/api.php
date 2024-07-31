<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix("v3")->group(function () {
    Route::prefix('auth')->group(base_path('routes/v3/api/auth.php'));
    Route::prefix('vendors')->group(base_path('routes/v3/api/vendors.php'));
});
