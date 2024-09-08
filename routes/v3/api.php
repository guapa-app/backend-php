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

Route::prefix('v3')->group(function () {
    Route::prefix('auth')->group(base_path('routes/v3/api/auth.php'));
    Route::prefix('vendors')->group(base_path('routes/v3/api/vendors.php'));
    Route::prefix('clients')->group(base_path('routes/v3/api/clients.php'));
    Route::prefix('support-msg')->group(base_path('routes/v3/api/support.php'));
    Route::prefix('coupons')->group(base_path('routes/v3/api/coupons.php'));
    Route::prefix('orders')->group(base_path('routes/v3/api/orders.php'));
    Route::prefix('pages')->group(base_path('routes/v3/api/pages.php'));
    Route::prefix('cities')->group(base_path('routes/v3/api/cities.php'));
    Route::prefix('products')->group(base_path('routes/v3/api/products.php'));
    Route::prefix('favorites')->group(base_path('routes/v3/api/favorites.php'));
    Route::prefix('social-media')->group(base_path('routes/v3/api/social_media.php'));
    Route::prefix('posts')->group(base_path('routes/v3/api/posts.php'));
    Route::prefix('campaigns')->group(base_path('routes/v3/api/campaigns.php'));
});
