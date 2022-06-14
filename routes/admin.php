<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "admin" middleware group. Enjoy building your Admin API!
|
*/

Route::post('auth/login', 'AuthController@login');

Route::group(['middleware' => 'auth:admin'], function() {
    Route::get('auth/admin', function(Request $request) {
        return response()->json(auth()->user());
    });

    Route::prefix('dashboard')->group(base_path('routes/admin/dashboard.php'));
    Route::prefix('admins')->group(base_path('routes/admin/admins.php'));
    Route::prefix('users')->group(base_path('routes/admin/users.php'));
    Route::prefix('vendors')->group(base_path('routes/admin/vendors.php'));
    Route::prefix('products')->group(base_path('routes/admin/products.php'));
    Route::prefix('offers')->group(base_path('routes/admin/offers.php'));
    Route::prefix('taxonomies')->group(base_path('routes/admin/taxonomies.php'));
    Route::prefix('addresses')->group(base_path('routes/admin/addresses.php'));
    Route::prefix('posts')->group(base_path('routes/admin/posts.php'));
    Route::prefix('comments')->group(base_path('routes/admin/comments.php'));
    Route::prefix('history')->group(base_path('routes/admin/history.php'));
    Route::prefix('reviews')->group(base_path('routes/admin/reviews.php'));
    Route::prefix('pages')->group(base_path('routes/admin/pages.php'));
    Route::prefix('settings')->group(base_path('routes/admin/settings.php'));
    Route::prefix('messages')->group(base_path('routes/admin/messages.php'));
    Route::prefix('cities')->group(base_path('routes/admin/cities.php'));
    Route::prefix('orders')->group(base_path('routes/admin/orders.php'));
    Route::post('devices', 'DeviceController@addDevice');
});