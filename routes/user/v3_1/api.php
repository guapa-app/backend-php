<?php

use App\Http\Controllers\Api\User\V3_1\TestApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CountryHeader;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Api\User\V3_1\DataController;
use App\Http\Controllers\Api\User\V3_1\HomeController;
use App\Http\Controllers\Api\User\V3_1\MediaController;
use App\Http\Controllers\Api\User\V3_1\OrderController;
use App\Http\Controllers\Api\User\V3_1\DeviceController;
use App\Http\Controllers\Api\User\V3_1\WalletController;
use App\Http\Controllers\Api\User\V3_1\CountryController;
use App\Http\Controllers\Api\User\V3_1\PaymentController;
use App\Http\Controllers\Api\User\V3_1\GiftCardController;
use App\Http\Controllers\Api\User\V3_1\TransactionController;
use App\Http\Controllers\Api\User\V3_1\LoyaltyPointsController;
use App\Http\Controllers\Api\User\V3_1\WheelOfFortuneController;
use App\Http\Controllers\Api\User\V3_1\WalletChargingPackageController;

Route::prefix("user/v3.1")->middleware([CountryHeader::class])->group(function () {
    Route::get('home', [HomeController::class, 'index']);
    Route::get('countries', [CountryController::class, 'index']);

    Route::prefix('auth')->group(base_path('routes/user/v3_1/api/auth.php'));
    Route::prefix('users')->group(base_path('routes/user/v3_1/api/users.php'));
    Route::prefix('posts')->group(base_path('routes/user/v3_1/api/posts.php'));
    Route::prefix('pages')->group(base_path('routes/user/v3_1/api/pages.php'));
    Route::prefix('offers')->group(base_path('routes/user/v3_1/api/offers.php'));
    Route::prefix('orders')->group(base_path('routes/user/v3_1/api/orders.php'));
    Route::prefix('coupons')->group(base_path('routes/user/v3_1/api/coupons.php'));
    Route::prefix('vendors')->group(base_path('routes/user/v3_1/api/vendors.php'));
    Route::prefix('history')->group(base_path('routes/user/v3_1/api/history.php'));
    Route::prefix('reviews')->group(base_path('routes/user/v3_1/api/reviews.php'));
    Route::prefix('products')->group(base_path('routes/user/v3_1/api/products.php'));
    Route::prefix('comments')->group(base_path('routes/user/v3_1/api/comments.php'));
    Route::prefix('favorites')->group(base_path('routes/user/v3_1/api/favorites.php'));
    Route::prefix('social-media')->group(base_path('routes/user/v3_1/api/social_media.php'));
    Route::prefix('addresses')->group(base_path('routes/user/v3_1/api/addresses.php'));
    Route::prefix('cities')->group(base_path('routes/user/v3_1/api/cities.php'));
    Route::prefix('messaging')->group(base_path('routes/user/v3_1/api/messaging.php'));
    Route::prefix('taxonomies')->group(base_path('routes/user/v3_1/api/taxonomies.php'));
    Route::prefix('notifications')->group(base_path('routes/user/v3_1/api/notifications.php'));
    Route::prefix('support-msg')->group(base_path('routes/user/v3_1/api/support.php'));
    Route::prefix('appointments')->group(base_path('routes/user/v3_1/api/appointments.php'));
    // consultation
    Route::prefix('consultations')->group(base_path('routes/user/v3_1/api/consultations.php'));

    Route::post('devices', [DeviceController::class, 'addDevice'])->middleware('auth:api');
    Route::get('data', [DataController::class, 'data']);
    Route::get('address_types', [DataController::class, 'address_types']);
    Route::get('vendor_types', [DataController::class, 'vendor_types']);
    Route::get('pages', [BaseApiController::class, 'pages']);
    Route::post('invoices/change-status', [OrderController::class, 'changeInvoiceStatus']);
    Route::get('gift-card-options', [DataController::class, 'giftCardOptions']);

    // Testing endpoints for order notifications
    Route::prefix('test')->group(function () {
        Route::get('basic', [TestApiController::class, 'testBasic']); // No auth for basic testing
        Route::post('order-notification', [TestApiController::class, 'sendTestOrderNotification'])->middleware('auth:api');
        Route::get('order-details', [TestApiController::class, 'getTestOrderDetails'])->middleware('auth:api');
    });


    Route::middleware('auth:api')->group(function () {

        Route::post('payment/change-status', [PaymentController::class, 'changePaymentStatus']);
        Route::post('payment/pay-via-wallet', [PaymentController::class, 'payViaWallet']);

        // Wallet Charging Packages
        Route::get('wallet-charging-packages', [WalletChargingPackageController::class, 'index']);

        // Wheel Of Fortune
        Route::get('wheel-of-fortune', [WheelOfFortuneController::class, 'index']);
        Route::post('wheel-of-fortune/spin', [WheelOfFortuneController::class, 'spinWheel']);
        Route::get('wheel-of-fortune/last-spin-date', [WheelOfFortuneController::class, 'lastSpinWheelDate']);

        // Wallet
        Route::get('wallet', [WalletController::class, 'show']);
        Route::post('wallet/charge', [WalletController::class, 'charge']);

        // Transaction
        Route::get('transactions', [TransactionController::class, 'index']);
        Route::get('transactions/{transaction}', [TransactionController::class, 'show']);

        // Loyalty Points
        Route::get('loyalty-points', [LoyaltyPointsController::class, 'totalPoints']);
        Route::get('loyalty-points/history', [LoyaltyPointsController::class, 'pointsHistory']);
        Route::post('loyalty-points/convert-points', [LoyaltyPointsController::class, 'convertPoints']);
        Route::post('loyalty-points/calc-convert-points', [LoyaltyPointsController::class, 'calcConvertPointsToCash']);

        // upload temporary media
        Route::post('media/upload-temporary', [MediaController::class, 'uploadTemporaryMedia']);

        // Gift Cards
        Route::prefix('gift-cards')->group(function () {
            Route::get('/options', [GiftCardController::class, 'options']);
            Route::get('/my', [GiftCardController::class, 'myGiftCards']);
            Route::get('/code', [GiftCardController::class, 'getByCode']);
            Route::get('/', [GiftCardController::class, 'index']);
            Route::post('/', [GiftCardController::class, 'store']);
            Route::post('/{id}/redeem-wallet', [GiftCardController::class, 'redeemToWallet']);
            Route::post('/{id}/create-order', [GiftCardController::class, 'createOrder']);
            Route::post('/{id}/cancel-order-redeem-wallet', [GiftCardController::class, 'cancelOrderAndRedeemToWallet']);
            Route::get('/{id}', [GiftCardController::class, 'show']);

            // QR Code functionality
            Route::get('/{id}/qr-code', [GiftCardController::class, 'generateQrCode']);
            Route::get('/{id}/qr-code/download', [GiftCardController::class, 'downloadQrCode']);
            Route::post('/verify-qr-code', [GiftCardController::class, 'verifyQrCode']);
        });
    });
});
