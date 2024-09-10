<?php

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Api\Vendor\V3_1\DeviceController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\Vendor\V3_1\DataController;
use App\Http\Controllers\Api\Vendor\V3_1\HomeController;
use Illuminate\Support\Facades\Route;

Route::prefix("vendor/v3.1")->group(function () {
    Route::get('home', [HomeController::class, 'index'])->middleware('auth:api');

    Route::prefix('auth')->group(base_path('routes/vendor/v3_1/api/auth.php'));
    Route::prefix('users')->group(base_path('routes/vendor/v3_1/api/users.php'));
    Route::prefix('staff')->group(base_path('routes/vendor/v3_1/api/staff.php'));
    Route::prefix('posts')->group(base_path('routes/vendor/v3_1/api/posts.php'));
    Route::prefix('offers')->group(base_path('routes/vendor/v3_1/api/offers.php'));
    Route::prefix('orders')->group(base_path('routes/vendor/v3_1/api/orders.php'));
    Route::prefix('vendors')->group(base_path('routes/vendor/v3_1/api/vendors.php'));
    Route::prefix('clients')->group(base_path('routes/vendor/v3_1/api/clients.php'));
    Route::prefix('history')->group(base_path('routes/vendor/v3_1/api/history.php'));
    Route::prefix('reviews')->group(base_path('routes/vendor/v3_1/api/reviews.php'));
    Route::prefix('products')->group(base_path('routes/vendor/v3_1/api/products.php'));
    Route::prefix('comments')->group(base_path('routes/vendor/v3_1/api/comments.php'));
    Route::prefix('favorites')->group(base_path('routes/vendor/v3_1/api/favorites.php'));
    Route::prefix('addresses')->group(base_path('routes/vendor/v3_1/api/addresses.php'));
    Route::prefix('messaging')->group(base_path('routes/vendor/v3_1/api/messaging.php'));
    Route::prefix('taxonomies')->group(base_path('routes/vendor/v3_1/api/taxonomies.php'));
    Route::prefix('notifications')->group(base_path('routes/vendor/v3_1/api/notifications.php'));
    Route::prefix('coupons')->group(base_path('routes/vendor/v3_1/api/coupons.php'));
    Route::prefix('')->group(base_path('routes/vendor/v3_1/api/support.php'));
    Route::prefix('campaigns')->group(base_path('routes/vendor/v3_1/api/campaigns.php'));

    Route::post('devices', [DeviceController::class, 'addDevice'])->middleware('auth:api');
    Route::get('data', [DataController::class, 'data']);
    Route::get('address_types', [DataController::class, 'address_types']);
    Route::get('vendor_types', [DataController::class, 'vendor_types']);
    Route::get('pages', [BaseApiController::class, 'pages']);
    Route::post('invoices/change-status', [OrderController::class, 'changeInvoiceStatus']);
});
