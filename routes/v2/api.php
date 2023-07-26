<?php

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\V2\DataController;
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

Route::prefix("v2")->group(function () {
    Route::prefix('auth')->group(base_path('routes/v2/api/auth.php'));
    Route::prefix('users')->group(base_path('routes/v2/api/users.php'));
    Route::prefix('staff')->group(base_path('routes/v2/api/staff.php'));
    Route::prefix('posts')->group(base_path('routes/v2/api/posts.php'));
    Route::prefix('offers')->group(base_path('routes/v2/api/offers.php'));
    Route::prefix('orders')->group(base_path('routes/v2/api/orders.php'));
    Route::prefix('vendors')->group(base_path('routes/v2/api/vendors.php'));
    Route::prefix('history')->group(base_path('routes/v2/api/history.php'));
    Route::prefix('reviews')->group(base_path('routes/v2/api/reviews.php'));
    Route::prefix('products')->group(base_path('routes/v2/api/products.php'));
    Route::prefix('comments')->group(base_path('routes/v2/api/comments.php'));
    Route::prefix('favorites')->group(base_path('routes/v2/api/favorites.php'));
    Route::prefix('addresses')->group(base_path('routes/v2/api/addresses.php'));
    Route::prefix('messaging')->group(base_path('routes/v2/api/messaging.php'));
    Route::prefix('taxonomies')->group(base_path('routes/v2/api/taxonomies.php'));
    Route::prefix('notifications')->group(base_path('routes/v2/api/notifications.php'));

    Route::post('devices',                  [DeviceController::class, 'addDevice'])->middleware('auth:api');
    Route::post('contact',                  [BaseApiController::class, 'contact'])->middleware('auth:api');
    Route::get('data',                      [DataController::class, 'data']);
    Route::get('pages',                     [BaseApiController::class, 'pages']);
    Route::post('invoices/change-status',   [OrderController::class, 'changeInvoiceStatus']);

//     Route::post('email/resend', [\App\Http\Controllers\Auth\VerificationController::class, 'resend'])
//         ->name('verification.resend');
});
