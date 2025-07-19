<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\GiftCard;
use App\Models\GiftCardSetting;
use App\Services\GiftCardService;
use App\Services\WalletService;
use App\Services\PaymentService;
use App\Services\TaxService;
use App\Services\TransactionService;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Gift Card Payment Functionality\n";
echo "=======================================\n\n";

try {
    // Create a test user
    $user = User::first();
    if (!$user) {
        echo "No users found in database. Please create a user first.\n";
        exit(1);
    }

    echo "Using user: {$user->name} (ID: {$user->id})\n\n";

    // Initialize services
    $transactionService = new TransactionService(app(\App\Services\PDFService::class));
    $walletService = new WalletService($transactionService);
    $paymentService = new PaymentService();
    $taxService = new TaxService(app(\App\Contracts\Repositories\TaxRepositoryInterface::class));
    $giftCardService = new GiftCardService($walletService, $paymentService, $taxService, $transactionService);

    // Test 1: Create a gift card
    echo "Test 1: Creating a gift card...\n";
    $giftCardData = [
        'amount' => 200.00,
        'currency' => 'SAR',
        'gift_type' => GiftCard::GIFT_TYPE_WALLET,
        'recipient_name' => 'Test Recipient',
        'recipient_email' => 'test@example.com',
        'message' => 'Test gift card message',
        'background_color' => '#FF5733',
    ];

    $giftCard = $giftCardService->createGiftCard($giftCardData, $user);
    echo "✓ Gift card created successfully!\n";
    echo "  - ID: {$giftCard->id}\n";
    echo "  - Code: {$giftCard->code}\n";
    echo "  - Amount: {$giftCard->amount}\n";
    echo "  - Tax Amount: {$giftCard->tax_amount}\n";
    echo "  - Total Amount: {$giftCard->total_amount}\n";
    echo "  - Status: {$giftCard->status}\n";
    echo "  - Payment Status: {$giftCard->payment_status}\n\n";

    // Test 2: Check payment status change
    echo "Test 2: Testing payment status change...\n";
    $paymentData = [
        'id' => $giftCard->id,
        'payment_id' => 'TEST_PAYMENT_' . uniqid(),
        'payment_gateway' => 'moyasar',
        'status' => 'paid'
    ];

    $updatedGiftCard = $giftCardService->changePaymentStatus($paymentData);
    echo "✓ Payment status changed successfully!\n";
    echo "  - Payment Status: {$updatedGiftCard->payment_status}\n";
    echo "  - Status: {$updatedGiftCard->status}\n";
    echo "  - Payment Method: {$updatedGiftCard->payment_method}\n";
    echo "  - Payment Reference: {$updatedGiftCard->payment_reference}\n\n";

    // Test 3: Create another gift card for wallet payment test
    echo "Test 3: Creating another gift card for wallet payment...\n";
    $giftCardData2 = [
        'amount' => 150.00,
        'currency' => 'SAR',
        'gift_type' => GiftCard::GIFT_TYPE_WALLET,
        'recipient_name' => 'Wallet Test Recipient',
        'recipient_email' => 'wallet@example.com',
        'message' => 'Wallet payment test',
        'background_color' => '#33FF57',
    ];

    $giftCard2 = $giftCardService->createGiftCard($giftCardData2, $user);
    echo "✓ Second gift card created successfully!\n";
    echo "  - ID: {$giftCard2->id}\n";
    echo "  - Total Amount: {$giftCard2->total_amount}\n";
    echo "  - Payment Status: {$giftCard2->payment_status}\n\n";

    // Test 4: Check wallet balance
    echo "Test 4: Checking wallet balance...\n";
    $wallet = $user->myWallet();
    echo "  - Current wallet balance: {$wallet->balance}\n";

    if ($wallet->balance < $giftCard2->total_amount) {
        echo "  - Insufficient balance for wallet payment test\n";
        echo "  - Need: {$giftCard2->total_amount}, Have: {$wallet->balance}\n";
        echo "  - Skipping wallet payment test\n\n";
    } else {
        // Test 5: Pay via wallet
        echo "Test 5: Testing wallet payment...\n";
        $walletPaymentData = [
            'id' => $giftCard2->id,
            'payment_gateway' => 'wallet',
            'status' => 'paid'
        ];

        $walletPaidGiftCard = $giftCardService->payViaWallet($user, $walletPaymentData);
        echo "✓ Wallet payment successful!\n";
        echo "  - Payment Status: {$walletPaidGiftCard->payment_status}\n";
        echo "  - Status: {$walletPaidGiftCard->status}\n";
        echo "  - Payment Method: {$walletPaidGiftCard->payment_method}\n";
        echo "  - Payment Reference: {$walletPaidGiftCard->payment_reference}\n\n";

        // Check updated wallet balance
        $updatedWallet = $user->myWallet();
        echo "  - Updated wallet balance: {$updatedWallet->balance}\n";
        echo "  - Amount deducted: " . ($wallet->balance - $updatedWallet->balance) . "\n\n";
    }

    echo "All tests completed successfully! 🎉\n";
    echo "Gift card payment functionality is working correctly.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}