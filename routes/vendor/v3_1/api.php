<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Api\Vendor\V3_1\DataController;
use App\Http\Controllers\Api\Vendor\V3_1\HomeController;
use App\Http\Controllers\Api\Vendor\V3_1\DeviceController;
use App\Http\Controllers\Api\Vendor\V3_1\WalletController;
use App\Http\Controllers\Api\Vendor\V3_1\CountryController;
use App\Http\Controllers\Api\Vendor\V3_1\GiftCardController;

Route::prefix("vendor/v3.1")->group(function () {
    Route::get('home', [HomeController::class, 'index'])->middleware('auth:api');
    Route::get('countries', [CountryController::class, 'index']);

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
    Route::prefix('products')->group(base_path('routes/vendor/v3_1/api/products.php'));
    Route::prefix('favorites')->group(base_path('routes/vendor/v3_1/api/favorites.php'));
    Route::prefix('addresses')->group(base_path('routes/vendor/v3_1/api/addresses.php'));
    Route::prefix('messaging')->group(base_path('routes/vendor/v3_1/api/messaging.php'));
    Route::prefix('taxonomies')->group(base_path('routes/vendor/v3_1/api/taxonomies.php'));
    Route::prefix('notifications')->group(base_path('routes/vendor/v3_1/api/notifications.php'));
    Route::prefix('coupons')->group(base_path('routes/vendor/v3_1/api/coupons.php'));
    Route::prefix('campaigns')->group(base_path('routes/vendor/v3_1/api/campaigns.php'));
    Route::prefix('support-msg')->group(base_path('routes/vendor/v3_1/api/support.php'));
    Route::prefix('appointments')->group(base_path('routes/vendor/v3_1/api/appointments.php'));
    Route::prefix('campaigns')->group(base_path('routes/vendor/v3_1/api/campaigns.php'));
    Route::prefix('')->group(base_path('routes/vendor/v3_1/api/doctors.php')); //sub-vendors
    Route::prefix('social-media')->group(base_path('routes/vendor/v3_1/api/social_media.php')); //sub-vendors
    Route::prefix('consultations')->group(base_path('routes/vendor/v3_1/api/consultations.php')); //sub-vendors

    Route::get('wallet', [WalletController::class, 'index'])->middleware('auth:api');
    Route::post('devices', [DeviceController::class, 'addDevice'])->middleware('auth:api');
    Route::get('data', [DataController::class, 'data']);
    Route::get('address_types', [DataController::class, 'address_types']);
    Route::get('vendor_types', [DataController::class, 'vendor_types']);
    Route::get('pages', [BaseApiController::class, 'pages']);
    Route::post('invoices/change-status', [OrderController::class, 'changeInvoiceStatus']);

    Route::prefix('gift-cards')->middleware('auth:api')->group(function () {
        Route::get('/', [GiftCardController::class, 'index']);
        Route::get('/{id}', [GiftCardController::class, 'show']);
    });

    // api for Amr
    Route::get('list-vendors-data', function () {
        $vendors = \App\Models\Vendor::with('addresses')->get()->map(function ($vendor) {
            return [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'phone' => $vendor->phone,
                'email' => $vendor->email,
                'status' => \App\Models\Vendor::STATUSES[$vendor->status],
                'addresses' => \App\Http\Resources\User\V3_1\AddressResource::collection($vendor->addresses),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Vendors data fetched successfully',
            'data' => $vendors,
        ]);
    });
});

Route::prefix('')->group(base_path('routes/vendor/v3_2/api.php'));
