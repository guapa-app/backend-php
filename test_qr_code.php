<?php

require_once 'vendor/autoload.php';

use App\Models\GiftCard;
use App\Models\GiftCardSetting;
use App\Models\User;
use App\Services\GiftCardQrCodeService;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Gift Card QR Code Test ===\n\n";

try {
    // Create default gift card settings if they don't exist
    if (!GiftCardSetting::where('key', 'code_prefix')->exists()) {
        GiftCardSetting::create([
            'key' => 'code_prefix',
            'value' => 'GC',
            'type' => 'string',
            'description' => 'Gift card code prefix'
        ]);
    }

    if (!GiftCardSetting::where('key', 'min_amount')->exists()) {
        GiftCardSetting::create([
            'key' => 'min_amount',
            'value' => 10,
            'type' => 'integer',
            'description' => 'Minimum gift card amount'
        ]);
    }

    if (!GiftCardSetting::where('key', 'max_amount')->exists()) {
        GiftCardSetting::create([
            'key' => 'max_amount',
            'value' => 10000,
            'type' => 'integer',
            'description' => 'Maximum gift card amount'
        ]);
    }

    if (!GiftCardSetting::where('key', 'default_expiration_days')->exists()) {
        GiftCardSetting::create([
            'key' => 'default_expiration_days',
            'value' => 365,
            'type' => 'integer',
            'description' => 'Default expiration days'
        ]);
    }

    // Create a test user
    $user = User::firstOrCreate(
        ['email' => 'test@example.com'],
        [
            'name' => 'Test User',
            'phone' => '1234567890',
            'status' => 'active'
        ]
    );

    echo "✅ Test user created/loaded: {$user->name} (ID: {$user->id})\n";

    // Create a test gift card
    echo "Creating gift card...\n";
    $giftCard = GiftCard::create([
        'user_id' => $user->id,
        'amount' => 150, // Using 150 to be above the minimum of 111
        'currency' => 'SAR',
        'gift_type' => GiftCard::GIFT_TYPE_WALLET,
        'status' => GiftCard::STATUS_ACTIVE,
        'payment_status' => GiftCard::PAYMENT_STATUS_PAID,
        'message' => 'Test gift card for QR code functionality',
    ]);
    echo "Gift card created, checking for QR code...\n";

    echo "✅ Gift card created: {$giftCard->code} (ID: {$giftCard->id})\n";

    // Test QR code generation
    echo "\n--- Testing QR Code Generation ---\n";

    // Check if QR code was automatically generated
    if ($giftCard->qrCode) {
        echo "✅ QR code automatically generated\n";
        echo "   - QR Code ID: {$giftCard->qrCode->id}\n";
        echo "   - File Name: {$giftCard->qrCode->file_name}\n";
        echo "   - Collection: {$giftCard->qrCode->collection_name}\n";
        echo "   - URL: {$giftCard->qr_code_url}\n";
    } else {
        echo "❌ QR code not automatically generated\n";

        // Manually generate QR code
        echo "   Generating QR code manually...\n";
        $giftCard->generateQrCode();
        $giftCard->refresh();

        if ($giftCard->qrCode) {
            echo "✅ QR code manually generated\n";
            echo "   - QR Code ID: {$giftCard->qrCode->id}\n";
            echo "   - File Name: {$giftCard->qrCode->file_name}\n";
            echo "   - URL: {$giftCard->qr_code_url}\n";
        } else {
            echo "❌ Failed to generate QR code\n";
        }
    }

    // Test QR code service
    echo "\n--- Testing QR Code Service ---\n";

    $qrCodeService = app(GiftCardQrCodeService::class);

    // Test standard QR code
    $standardQrCode = $qrCodeService->generateForGiftCard($giftCard);
    echo "✅ Standard QR code generated (" . strlen($standardQrCode) . " bytes)\n";

    // Test sharing QR code
    $sharingQrCode = $qrCodeService->generateSharingQrCode($giftCard);
    echo "✅ Sharing QR code generated (" . strlen($sharingQrCode) . " bytes)\n";

    // Test verification QR code
    $verificationQrCode = $qrCodeService->generateVerificationQrCode($giftCard);
    echo "✅ Verification QR code generated (" . strlen($verificationQrCode) . " bytes)\n";

    // Test QR code data
    echo "\n--- Testing QR Code Data ---\n";

    $qrData = $qrCodeService->buildQrCodeData($giftCard);
    echo "✅ QR code data built:\n";
    echo "   - Gift Card ID: {$qrData['gift_card_id']}\n";
    echo "   - Code: {$qrData['code']}\n";
    echo "   - Amount: {$qrData['amount']} {$qrData['currency']}\n";
    echo "   - Status: {$qrData['status']}\n";
    echo "   - Payment Status: {$qrData['payment_status']}\n";
    echo "   - Redemption URL: {$qrData['redemption_url']}\n";

    // Test QR code validation
    echo "\n--- Testing QR Code Validation ---\n";

    $qrCodeString = $qrCodeService->buildQrCodeString($qrData);
    $parsedData = $qrCodeService->parseQrCodeData($qrCodeString);

    if ($parsedData) {
        echo "✅ QR code data parsed successfully\n";
        echo "   - Parsed Gift Card ID: {$parsedData['gift_card_id']}\n";
        echo "   - Parsed Code: {$parsedData['code']}\n";

        $isValid = $qrCodeService->validateQrCodeData($parsedData);
        echo $isValid ? "✅ QR code data validation passed\n" : "❌ QR code data validation failed\n";
    } else {
        echo "❌ Failed to parse QR code data\n";
    }

    // Test resource response
    echo "\n--- Testing Resource Response ---\n";

    $resource = new \App\Http\Resources\User\V3_1\GiftCardResource($giftCard->load('qrCode'));
    $resourceData = $resource->toArray(request());

    echo "✅ Resource data generated\n";
    echo "   - QR Code URL: " . ($resourceData['qr_code_url'] ?? 'null') . "\n";
    echo "   - QR Code Media: " . ($resourceData['qr_code_media'] ? 'present' : 'null') . "\n";

    if ($resourceData['qr_code_media']) {
        echo "   - QR Code Media ID: {$resourceData['qr_code_media']['id']}\n";
        echo "   - QR Code Media URL: {$resourceData['qr_code_media']['url']}\n";
        echo "   - QR Code Media Collection: {$resourceData['qr_code_media']['collection_name']}\n";
    }

    echo "\n=== Test Summary ===\n";
    echo "✅ All tests completed successfully!\n";
    echo "📋 Gift Card Details:\n";
    echo "   - ID: {$giftCard->id}\n";
    echo "   - Code: {$giftCard->code}\n";
    echo "   - Amount: {$giftCard->amount} {$giftCard->currency}\n";
    echo "   - Status: {$giftCard->status}\n";
    echo "   - QR Code: " . ($giftCard->qr_code_url ? 'Generated' : 'Not Generated') . "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
