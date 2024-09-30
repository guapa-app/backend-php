<?php

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Api\User\V3_1\DeviceController;
use App\Http\Controllers\Api\User\V3_1\OrderController;
use App\Http\Controllers\Api\User\V3_1\DataController;
use App\Http\Controllers\Api\User\V3_1\HomeController;
use App\Http\Controllers\Api\User\V3_1\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix("user/v3.1")->group(function () {
    Route::get('home', [HomeController::class, 'index']);

    Route::prefix('auth')->group(base_path('routes/user/v3_1/api/auth.php'));
    Route::prefix('users')->group(base_path('routes/user/v3_1/api/users.php'));
    Route::prefix('staff')->group(base_path('routes/user/v3_1/api/staff.php'));
    Route::prefix('posts')->group(base_path('routes/user/v3_1/api/posts.php'));
    Route::prefix('pages')->group(base_path('routes/user/v3_1/api/pages.php'));
    Route::prefix('offers')->group(base_path('routes/user/v3_1/api/offers.php'));
    Route::prefix('orders')->group(base_path('routes/user/v3_1/api/orders.php'));
    Route::prefix('vendors')->group(base_path('routes/user/v3_1/api/vendors.php'));
    Route::prefix('history')->group(base_path('routes/user/v3_1/api/history.php'));
    Route::prefix('reviews')->group(base_path('routes/user/v3_1/api/reviews.php'));
    Route::prefix('products')->group(base_path('routes/user/v3_1/api/products.php'));
    Route::prefix('comments')->group(base_path('routes/user/v3_1/api/comments.php'));
    Route::prefix('favorites')->group(base_path('routes/user/v3_1/api/favorites.php'));
    Route::prefix('social-media')->group(base_path('routes/user/v3_1/api/social_media.php'));
    Route::prefix('addresses')->group(base_path('routes/user/v3_1/api/addresses.php'));
    Route::prefix('messaging')->group(base_path('routes/user/v3_1/api/messaging.php'));
    Route::prefix('taxonomies')->group(base_path('routes/user/v3_1/api/taxonomies.php'));
    Route::prefix('notifications')->group(base_path('routes/user/v3_1/api/notifications.php'));
    Route::prefix('support-msg')->group(base_path('routes/user/v3_1/api/support.php'));
    Route::prefix('appointments')->group(base_path('routes/user/v3_1/api/appointments.php'));

    Route::post('devices', [DeviceController::class, 'addDevice'])->middleware('auth:api');
    Route::get('data', [DataController::class, 'data']);
    Route::get('address_types', [DataController::class, 'address_types']);
    Route::get('vendor_types', [DataController::class, 'vendor_types']);
    Route::get('pages', [BaseApiController::class, 'pages']);
    Route::post('invoices/change-status', [OrderController::class, 'changeInvoiceStatus']);
    Route::post('payment/change-status', [PaymentController::class, 'changePaymentStatus']);
});
