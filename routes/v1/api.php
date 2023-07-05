<?php

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\OrderController;
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

Route::prefix("v1")->group(function () {
    Route::prefix('auth')->group(base_path('routes/v1/api/auth.php'));
    Route::prefix('users')->group(base_path('routes/v1/api/users.php'));
    Route::prefix('staff')->group(base_path('routes/v1/api/staff.php'));
    Route::prefix('posts')->group(base_path('routes/v1/api/posts.php'));
    Route::prefix('offers')->group(base_path('routes/v1/api/offers.php'));
    Route::prefix('orders')->group(base_path('routes/v1/api/orders.php'));
    Route::prefix('vendors')->group(base_path('routes/v1/api/vendors.php'));
    Route::prefix('history')->group(base_path('routes/v1/api/history.php'));
    Route::prefix('reviews')->group(base_path('routes/v1/api/reviews.php'));
    Route::prefix('products')->group(base_path('routes/v1/api/products.php'));
    Route::prefix('comments')->group(base_path('routes/v1/api/comments.php'));
    Route::prefix('favorites')->group(base_path('routes/v1/api/favorites.php'));
    Route::prefix('addresses')->group(base_path('routes/v1/api/addresses.php'));
    Route::prefix('messaging')->group(base_path('routes/v1/api/messaging.php'));
    Route::prefix('notifications')->group(base_path('routes/v1/api/notifications.php'));

    Route::post('devices',                  [DeviceController::class, 'addDevice'])->middleware('auth:api');
    Route::post('contact',                  [BaseApiController::class, 'contact'])->middleware('auth:api');
    Route::get('data',                      [BaseApiController::class, 'data']);
    Route::get('pages',                     [BaseApiController::class, 'pages']);
    Route::post('invoices/change-status',   [OrderController::class, 'changeInvoiceStatus']);

//     Route::post('email/resend', [\App\Http\Controllers\Auth\VerificationController::class, 'resend'])
//         ->name('verification.resend');
});
