<?php

namespace Tests\Feature;

use App\Models\GiftCard;
use App\Models\GiftCardSetting;
use App\Models\User;
use App\Services\GiftCardQrCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GiftCardQrCodeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create default gift card settings
        GiftCardSetting::create([
            'key' => 'code_prefix',
            'value' => 'GC',
            'type' => 'string',
            'description' => 'Gift card code prefix'
        ]);

        GiftCardSetting::create([
            'key' => 'min_amount',
            'value' => 10,
            'type' => 'integer',
            'description' => 'Minimum gift card amount'
        ]);

        GiftCardSetting::create([
            'key' => 'max_amount',
            'value' => 10000,
            'type' => 'integer',
            'description' => 'Maximum gift card amount'
        ]);

        GiftCardSetting::create([
            'key' => 'default_expiration_days',
            'value' => 365,
            'type' => 'integer',
            'description' => 'Default expiration days'
        ]);
    }

    public function test_gift_card_creates_qr_code_automatically()
    {
        $user = User::factory()->create();

        $giftCard = GiftCard::create([
            'user_id' => $user->id,
            'amount' => 100,
            'currency' => 'SAR',
            'gift_type' => GiftCard::GIFT_TYPE_WALLET,
            'status' => GiftCard::STATUS_ACTIVE,
            'payment_status' => GiftCard::PAYMENT_STATUS_PAID,
        ]);

        $this->assertNotNull($giftCard->qrCode);
        $this->assertNotNull($giftCard->qr_code_url);
    }

    public function test_qr_code_service_generates_valid_qr_code()
    {
        $user = User::factory()->create();

        $giftCard = GiftCard::create([
            'user_id' => $user->id,
            'amount' => 100,
            'currency' => 'SAR',
            'gift_type' => GiftCard::GIFT_TYPE_WALLET,
            'status' => GiftCard::STATUS_ACTIVE,
            'payment_status' => GiftCard::PAYMENT_STATUS_PAID,
        ]);

        $qrCodeService = app(GiftCardQrCodeService::class);
        $qrCodeImage = $qrCodeService->generateForGiftCard($giftCard);

        $this->assertNotEmpty($qrCodeImage);
        $this->assertStringStartsWith('data:image/png;base64,', 'data:image/png;base64,' . base64_encode($qrCodeImage));
    }

    public function test_qr_code_data_contains_required_fields()
    {
        $user = User::factory()->create();

        $giftCard = GiftCard::create([
            'user_id' => $user->id,
            'amount' => 100,
            'currency' => 'SAR',
            'gift_type' => GiftCard::GIFT_TYPE_WALLET,
            'status' => GiftCard::STATUS_ACTIVE,
            'payment_status' => GiftCard::PAYMENT_STATUS_PAID,
            'message' => 'Happy Birthday!',
        ]);

        $qrCodeService = app(GiftCardQrCodeService::class);
        $qrData = $qrCodeService->buildQrCodeData($giftCard);

        $this->assertArrayHasKey('gift_card_id', $qrData);
        $this->assertArrayHasKey('code', $qrData);
        $this->assertArrayHasKey('amount', $qrData);
        $this->assertArrayHasKey('status', $qrData);
        $this->assertArrayHasKey('payment_status', $qrData);
        $this->assertArrayHasKey('message', $qrData);
        $this->assertArrayHasKey('redemption_url', $qrData);

        $this->assertEquals($giftCard->id, $qrData['gift_card_id']);
        $this->assertEquals($giftCard->code, $qrData['code']);
        $this->assertEquals($giftCard->amount, $qrData['amount']);
        $this->assertEquals($giftCard->status, $qrData['status']);
        $this->assertEquals($giftCard->payment_status, $qrData['payment_status']);
        $this->assertEquals($giftCard->message, $qrData['message']);
    }

    public function test_qr_code_validation_works()
    {
        $qrCodeService = app(GiftCardQrCodeService::class);

        $validData = [
            'gift_card_id' => 1,
            'code' => 'GC123456',
            'amount' => 100,
            'status' => 'active',
            'payment_status' => 'paid',
        ];

        $this->assertTrue($qrCodeService->validateQrCodeData($validData));

        $invalidData = [
            'gift_card_id' => 1,
            'code' => 'GC123456',
            // Missing required fields
        ];

        $this->assertFalse($qrCodeService->validateQrCodeData($invalidData));
    }

    public function test_qr_code_parsing_works()
    {
        $user = User::factory()->create();

        $giftCard = GiftCard::create([
            'user_id' => $user->id,
            'amount' => 100,
            'currency' => 'SAR',
            'gift_type' => GiftCard::GIFT_TYPE_WALLET,
            'status' => GiftCard::STATUS_ACTIVE,
            'payment_status' => GiftCard::PAYMENT_STATUS_PAID,
        ]);

        $qrCodeService = app(GiftCardQrCodeService::class);
        $qrCodeString = $qrCodeService->buildQrCodeString($qrCodeService->buildQrCodeData($giftCard));

        $parsedData = $qrCodeService->parseQrCodeData($qrCodeString);

        $this->assertNotNull($parsedData);
        $this->assertEquals($giftCard->id, $parsedData['gift_card_id']);
        $this->assertEquals($giftCard->code, $parsedData['code']);
    }

    public function test_gift_card_api_endpoints_work()
    {
        $user = User::factory()->create();

        $giftCard = GiftCard::create([
            'user_id' => $user->id,
            'amount' => 100,
            'currency' => 'SAR',
            'gift_type' => GiftCard::GIFT_TYPE_WALLET,
            'status' => GiftCard::STATUS_ACTIVE,
            'payment_status' => GiftCard::PAYMENT_STATUS_PAID,
        ]);

        // Test show endpoint
        $response = $this->actingAs($user, 'api')
            ->getJson("/api/user/v3.1/gift-cards/{$giftCard->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'code',
                'amount',
                'qr_code_url',
                'redemption_url'
            ]
        ]);

        // Test QR code generation endpoint
        $response = $this->actingAs($user, 'api')
            ->getJson("/api/user/v3.1/gift-cards/{$giftCard->id}/qr-code");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'qr_code_url'
            ]
        ]);

        // Test QR code download endpoint
        $response = $this->actingAs($user, 'api')
            ->getJson("/api/user/v3.1/gift-cards/{$giftCard->id}/qr-code/download");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'download_url',
                'filename'
            ]
        ]);
    }

    public function test_gift_card_resource_includes_qr_code_media()
    {
        $user = User::factory()->create();

        $giftCard = GiftCard::create([
            'user_id' => $user->id,
            'amount' => 100,
            'currency' => 'SAR',
            'gift_type' => GiftCard::GIFT_TYPE_WALLET,
            'status' => GiftCard::STATUS_ACTIVE,
            'payment_status' => GiftCard::PAYMENT_STATUS_PAID,
        ]);

        // Test that the resource includes QR code media
        $response = $this->actingAs($user, 'api')
            ->getJson("/api/user/v3.1/gift-cards/{$giftCard->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'code',
                'amount',
                'qr_code_url',
                'qr_code_media' => [
                    'id',
                    'file_name',
                    'mime_type',
                    'size',
                    'collection_name',
                    'url',
                    'large',
                    'medium',
                    'small'
                ]
            ]
        ]);

        // Verify QR code media data
        $responseData = $response->json('data');
        $this->assertNotNull($responseData['qr_code_media']);
        $this->assertEquals('gift_card_qr_codes', $responseData['qr_code_media']['collection_name']);
        $this->assertEquals('image/png', $responseData['qr_code_media']['mime_type']);
        $this->assertStringContainsString('gift_card_qr_', $responseData['qr_code_media']['file_name']);
        $this->assertNotNull($responseData['qr_code_media']['url']);
    }
}
