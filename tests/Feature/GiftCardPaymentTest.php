<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\GiftCard;
use App\Services\GiftCardService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GiftCardPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $giftCardService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->giftCardService = app(GiftCardService::class);
    }

    /** @test */
    public function it_can_create_gift_card_with_pending_payment_status()
    {
        $data = [
            'amount' => 100,
            'currency' => 'SAR',
            'gift_type' => GiftCard::GIFT_TYPE_WALLET,
            'message' => 'Test gift card',
            'recipient_name' => 'John Doe',
            'recipient_email' => 'john@example.com',
        ];

        $giftCard = $this->giftCardService->createGiftCard($data, $this->user);

        $this->assertEquals(GiftCard::STATUS_PENDING, $giftCard->status);
        $this->assertEquals(GiftCard::PAYMENT_STATUS_PENDING, $giftCard->payment_status);
        $this->assertEquals(100, $giftCard->amount);
        $this->assertEquals(15, $giftCard->tax_amount); // 15% tax
        $this->assertEquals(115, $giftCard->total_amount);
    }

    /** @test */
    public function it_can_change_payment_status_to_paid()
    {
        $giftCard = GiftCard::factory()->create([
            'sender_id' => $this->user->id,
            'status' => GiftCard::STATUS_PENDING,
            'payment_status' => GiftCard::PAYMENT_STATUS_PENDING,
            'amount' => 100,
            'tax_amount' => 15,
            'total_amount' => 115,
        ]);

        $data = [
            'id' => $giftCard->id,
            'payment_id' => 'test_payment_123',
            'payment_gateway' => 'moyasar',
            'status' => 'paid',
        ];

        $updatedGiftCard = $this->giftCardService->changePaymentStatus($data);

        $this->assertEquals(GiftCard::STATUS_ACTIVE, $updatedGiftCard->status);
        $this->assertEquals(GiftCard::PAYMENT_STATUS_PAID, $updatedGiftCard->payment_status);
        $this->assertEquals('test_payment_123', $updatedGiftCard->payment_reference);
        $this->assertEquals('credit_card', $updatedGiftCard->payment_method);
        $this->assertEquals('moyasar', $updatedGiftCard->payment_gateway);
    }

    /** @test */
    public function it_can_pay_via_wallet()
    {
        // Create user with wallet balance
        $this->user->myWallet()->update(['balance' => 200]);

        $giftCard = GiftCard::factory()->create([
            'sender_id' => $this->user->id,
            'status' => GiftCard::STATUS_PENDING,
            'payment_status' => GiftCard::PAYMENT_STATUS_PENDING,
            'amount' => 100,
            'tax_amount' => 15,
            'total_amount' => 115,
        ]);

        $data = [
            'id' => $giftCard->id,
        ];

        $updatedGiftCard = $this->giftCardService->payViaWallet($this->user, $data);

        $this->assertEquals(GiftCard::STATUS_ACTIVE, $updatedGiftCard->status);
        $this->assertEquals(GiftCard::PAYMENT_STATUS_PAID, $updatedGiftCard->payment_status);
        $this->assertEquals('wallet', $updatedGiftCard->payment_method);
        $this->assertEquals('wallet', $updatedGiftCard->payment_gateway);

        // Check wallet balance was deducted
        $this->assertEquals(85, $this->user->myWallet()->fresh()->balance);
    }

    /** @test */
    public function it_prevents_double_payment()
    {
        $giftCard = GiftCard::factory()->create([
            'sender_id' => $this->user->id,
            'status' => GiftCard::STATUS_ACTIVE,
            'payment_status' => GiftCard::PAYMENT_STATUS_PAID,
        ]);

        $data = [
            'id' => $giftCard->id,
            'payment_id' => 'test_payment_123',
            'payment_gateway' => 'moyasar',
            'status' => 'paid',
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Payment already processed for this gift card');

        $this->giftCardService->changePaymentStatus($data);
    }

    /** @test */
    public function it_prevents_wallet_payment_with_insufficient_balance()
    {
        // Create user with insufficient wallet balance
        $this->user->myWallet()->update(['balance' => 50]);

        $giftCard = GiftCard::factory()->create([
            'sender_id' => $this->user->id,
            'status' => GiftCard::STATUS_PENDING,
            'payment_status' => GiftCard::PAYMENT_STATUS_PENDING,
            'amount' => 100,
            'tax_amount' => 15,
            'total_amount' => 115,
        ]);

        $data = [
            'id' => $giftCard->id,
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient wallet balance');

        $this->giftCardService->payViaWallet($this->user, $data);
    }
}
