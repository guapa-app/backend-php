<?php

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Api\v3\DeviceController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\v3\DataController;
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
    Route::prefix('users')->group(base_path('routes/v3/api/users.php'));
    Route::prefix('staff')->group(base_path('routes/v3/api/staff.php'));
    Route::prefix('posts')->group(base_path('routes/v3/api/posts.php'));
    Route::prefix('offers')->group(base_path('routes/v3/api/offers.php'));
    Route::prefix('orders')->group(base_path('routes/v3/api/orders.php'));
    Route::prefix('vendors')->group(base_path('routes/v3/api/vendors.php'));
    Route::prefix('history')->group(base_path('routes/v3/api/history.php'));
    Route::prefix('reviews')->group(base_path('routes/v3/api/reviews.php'));
    Route::prefix('products')->group(base_path('routes/v3/api/products.php'));
    Route::prefix('comments')->group(base_path('routes/v3/api/comments.php'));
    Route::prefix('favorites')->group(base_path('routes/v3/api/favorites.php'));
    Route::prefix('addresses')->group(base_path('routes/v3/api/addresses.php'));
    Route::prefix('messaging')->group(base_path('routes/v3/api/messaging.php'));
    Route::prefix('taxonomies')->group(base_path('routes/v3/api/taxonomies.php'));
    Route::prefix('notifications')->group(base_path('routes/v3/api/notifications.php'));

    Route::post('devices',                  [DeviceController::class, 'addDevice'])->middleware('auth:api');
    Route::post('contact',                  [BaseApiController::class, 'contact'])->middleware('auth:api');
    Route::get('data',                      [DataController::class, 'data']);
    Route::get('address_types',             [DataController::class, 'address_types']);
    Route::get('vendor_types',              [DataController::class, 'vendor_types']);
    Route::get('pages',                     [BaseApiController::class, 'pages']);
    Route::post('invoices/change-status',   [OrderController::class, 'changeInvoiceStatus']);

//     Route::post('email/resend', [\App\Http\Controllers\Auth\VerificationController::class, 'resend'])
//         ->name('verification.resend');
});
