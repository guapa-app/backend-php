<?php

declare(strict_types=1);

namespace Tests\Feature\V3;

use Tests\TestCase;
use App\Models\User;
use App\Models\GiftCard;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Offer;
use App\Models\GiftCardBackground;
use App\Models\GiftCardSetting;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GiftCardApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed'); // Seed the database for real data

        // Create necessary settings
        GiftCardSetting::create([
            'key' => 'background_colors',
            'value' => ['#FF8B85', '#FFB6C1', '#87CEEB', '#98FB98'],
            'type' => 'array'
        ]);

        GiftCardSetting::create([
            'key' => 'suggested_amounts',
            'value' => [50, 100, 200, 500],
            'type' => 'array'
        ]);

        GiftCardSetting::create([
            'key' => 'currencies',
            'value' => ['SAR', 'USD', 'EUR'],
            'type' => 'array'
        ]);

        GiftCardSetting::create([
            'key' => 'min_amount',
            'value' => 10,
            'type' => 'integer'
        ]);

        GiftCardSetting::create([
            'key' => 'max_amount',
            'value' => 10000,
            'type' => 'integer'
        ]);

        GiftCardSetting::create([
            'key' => 'default_currency',
            'value' => 'SAR',
            'type' => 'string'
        ]);

        GiftCardSetting::create([
            'key' => 'code_prefix',
            'value' => 'GC',
            'type' => 'string'
        ]);
    }

    /** @test */
    public function user_can_get_gift_card_options()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/v3.1/user/gift-cards/options');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'gift_type',
                    'background_colors',
                    'background_images',
                    'suggested_amounts',
                    'currencies',
                    'min_amount',
                    'max_amount',
                    'default_currency',
                ],
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'gift_type' => [
                        'wallet' => 'Wallet Credit',
                        'order' => 'Order',
                    ],
                ]
            ]);
    }

    /** @test */
    public function user_can_get_their_sent_and_received_gift_cards()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/v3.1/user/gift-cards/my?type=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'current_page',
                    'data',
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url',
                    'links',
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total',
                ],
            ]);
    }

    /** @test */
    public function user_can_get_sent_gift_cards_only()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/v3.1/user/gift-cards/my?type=sent');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ]);
    }

    /** @test */
    public function user_can_get_received_gift_cards_only()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/v3.1/user/gift-cards/my?type=received');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ]);
    }

    /** @test */
    public function user_can_create_wallet_type_gift_card()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $giftCardData = [
            'gift_type' => 'wallet',
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Happy Birthday!',
            'recipient_name' => 'John Doe',
            'recipient_email' => 'john@example.com',
            'expires_at' => now()->addDays(30)->toDateString(),
        ];

        $response = $this->postJson('/api/v3.1/user/gift-cards', $giftCardData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'code',
                    'gift_type',
                    'amount',
                    'currency',
                    'background_color',
                    'message',
                    'status',
                    'recipient_name',
                    'recipient_email',
                    'sender_id',
                    'created_at',
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'gift_type' => 'wallet',
                    'amount' => '100.00',
                    'currency' => 'SAR',
                    'background_color' => '#FF8B85',
                    'message' => 'Happy Birthday!',
                    'recipient_name' => 'John Doe',
                    'recipient_email' => 'john@example.com',
                    'sender_id' => $user->id,
                ]
            ]);

        $this->assertDatabaseHas('gift_cards', [
            'gift_type' => 'wallet',
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Happy Birthday!',
            'recipient_name' => 'John Doe',
            'recipient_email' => 'john@example.com',
            'sender_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_create_order_type_gift_card_with_product()
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);

        Sanctum::actingAs($user, ['*']);

        $giftCardData = [
            'gift_type' => 'order',
            'product_id' => $product->id,
            'vendor_id' => $vendor->id,
            'amount' => 200,
            'currency' => 'SAR',
            'background_color' => '#87CEEB',
            'message' => 'Enjoy your gift!',
            'recipient_name' => 'Jane Smith',
            'recipient_email' => 'jane@example.com',
        ];

        $response = $this->postJson('/api/v3.1/user/gift-cards', $giftCardData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'gift_type' => 'order',
                    'product_id' => $product->id,
                    'vendor_id' => $vendor->id,
                    'amount' => '200.00',
                    'currency' => 'SAR',
                    'background_color' => '#87CEEB',
                    'message' => 'Enjoy your gift!',
                    'recipient_name' => 'Jane Smith',
                    'recipient_email' => 'jane@example.com',
                ]
            ]);

        $this->assertDatabaseHas('gift_cards', [
            'gift_type' => 'order',
            'product_id' => $product->id,
            'vendor_id' => $vendor->id,
            'amount' => 200,
            'currency' => 'SAR',
            'background_color' => '#87CEEB',
            'message' => 'Enjoy your gift!',
            'recipient_name' => 'Jane Smith',
            'recipient_email' => 'jane@example.com',
        ]);
    }

    /** @test */
    public function user_can_create_order_type_gift_card_with_offer()
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create();
        $offer = Offer::factory()->create(['vendor_id' => $vendor->id]);

        Sanctum::actingAs($user, ['*']);

        $giftCardData = [
            'gift_type' => 'order',
            'offer_id' => $offer->id,
            'vendor_id' => $vendor->id,
            'amount' => 150,
            'currency' => 'SAR',
            'background_color' => '#98FB98',
            'message' => 'Special offer for you!',
            'recipient_name' => 'Bob Wilson',
            'recipient_email' => 'bob@example.com',
        ];

        $response = $this->postJson('/api/v3.1/user/gift-cards', $giftCardData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'gift_type' => 'order',
                    'offer_id' => $offer->id,
                    'vendor_id' => $vendor->id,
                    'amount' => '150.00',
                    'currency' => 'SAR',
                    'background_color' => '#98FB98',
                    'message' => 'Special offer for you!',
                    'recipient_name' => 'Bob Wilson',
                    'recipient_email' => 'bob@example.com',
                ]
            ]);

        $this->assertDatabaseHas('gift_cards', [
            'gift_type' => 'order',
            'offer_id' => $offer->id,
            'vendor_id' => $vendor->id,
            'amount' => 150,
            'currency' => 'SAR',
            'background_color' => '#98FB98',
            'message' => 'Special offer for you!',
            'recipient_name' => 'Bob Wilson',
            'recipient_email' => 'bob@example.com',
        ]);
    }

    /** @test */
    public function user_can_create_gift_card_with_background_image()
    {
        $user = User::factory()->create();
        $background = GiftCardBackground::factory()->create(['is_active' => true]);

        Sanctum::actingAs($user, ['*']);

        $giftCardData = [
            'gift_type' => 'wallet',
            'amount' => 75,
            'currency' => 'SAR',
            'background_image_id' => $background->id,
            'message' => 'Beautiful background!',
            'recipient_name' => 'Alice Brown',
            'recipient_email' => 'alice@example.com',
        ];

        $response = $this->postJson('/api/v3.1/user/gift-cards', $giftCardData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'gift_type' => 'wallet',
                    'amount' => '75.00',
                    'currency' => 'SAR',
                    'background_image_id' => $background->id,
                    'message' => 'Beautiful background!',
                    'recipient_name' => 'Alice Brown',
                    'recipient_email' => 'alice@example.com',
                ]
            ]);

        $this->assertDatabaseHas('gift_cards', [
            'gift_type' => 'wallet',
            'amount' => 75,
            'currency' => 'SAR',
            'background_image_id' => $background->id,
            'message' => 'Beautiful background!',
            'recipient_name' => 'Alice Brown',
            'recipient_email' => 'alice@example.com',
        ]);
    }

    /** @test */
    public function user_can_create_gift_card_with_new_user()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $giftCardData = [
            'gift_type' => 'wallet',
            'amount' => 50,
            'currency' => 'SAR',
            'background_color' => '#FFB6C1',
            'message' => 'Welcome!',
            'recipient_name' => 'New User',
            'recipient_email' => 'newuser@example.com',
            'create_new_user' => true,
            'new_user_name' => 'New User',
            'new_user_phone' => '+966501234567',
            'new_user_email' => 'newuser@example.com',
        ];

        $response = $this->postJson('/api/v3.1/user/gift-cards', $giftCardData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'gift_type' => 'wallet',
                    'amount' => '50.00',
                    'currency' => 'SAR',
                    'background_color' => '#FFB6C1',
                    'message' => 'Welcome!',
                    'recipient_name' => 'New User',
                    'recipient_email' => 'newuser@example.com',
                ]
            ]);

        // Check that new user was created
        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'phone' => '+966501234567',
            'email' => 'newuser@example.com',
        ]);

        // Check that gift card was created with the new user
        $newUser = User::where('phone', '+966501234567')->first();
        $this->assertDatabaseHas('gift_cards', [
            'gift_type' => 'wallet',
            'amount' => 50,
            'currency' => 'SAR',
            'background_color' => '#FFB6C1',
            'message' => 'Welcome!',
            'recipient_name' => 'New User',
            'recipient_email' => 'newuser@example.com',
            'user_id' => $newUser->id,
        ]);
    }

    /** @test */
    public function user_can_get_specific_gift_card()
    {
        $user = User::factory()->create();
        $giftCard = GiftCard::factory()->create([
            'sender_id' => $user->id,
            'gift_type' => 'wallet',
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson("/api/v3.1/user/gift-cards/{$giftCard->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'code',
                    'gift_type',
                    'gift_type_label',
                    'amount',
                    'currency',
                    'background_color',
                    'message',
                    'status',
                    'status_label',
                    'redemption_method',
                    'recipient_name',
                    'recipient_email',
                    'sender_id',
                    'created_at',
                    'updated_at',
                    'is_wallet_type',
                    'is_order_type',
                    'is_redeemed',
                    'is_expired',
                    'can_be_redeemed',
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $giftCard->id,
                    'gift_type' => 'wallet',
                    'amount' => '100.00',
                    'currency' => 'SAR',
                    'background_color' => '#FF8B85',
                    'message' => 'Test gift card',
                    'recipient_name' => 'Test Recipient',
                    'recipient_email' => 'test@example.com',
                    'sender_id' => $user->id,
                    'is_wallet_type' => true,
                    'is_order_type' => false,
                ]
            ]);
    }

    /** @test */
    public function user_can_get_gift_card_by_code()
    {
        $user = User::factory()->create();
        $giftCard = GiftCard::factory()->create([
            'sender_id' => $user->id,
            'code' => 'GC123456',
            'gift_type' => 'wallet',
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/v3.1/user/gift-cards/code?code=GC123456');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $giftCard->id,
                    'code' => 'GC123456',
                    'gift_type' => 'wallet',
                    'amount' => '100.00',
                    'currency' => 'SAR',
                    'background_color' => '#FF8B85',
                    'message' => 'Test gift card',
                    'recipient_name' => 'Test Recipient',
                    'recipient_email' => 'test@example.com',
                ]
            ]);
    }

    /** @test */
    public function get_gift_card_by_code_returns_404_for_invalid_code()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/v3.1/user/gift-cards/code?code=INVALID123');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => __('api.gift_card_not_found'),
            ]);
    }

    /** @test */
    public function user_cannot_access_other_users_gift_card()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $giftCard = GiftCard::factory()->create([
            'sender_id' => $user1->id,
            'gift_type' => 'wallet',
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
        ]);

        Sanctum::actingAs($user2, ['*']);

        $response = $this->getJson("/api/v3.1/user/gift-cards/{$giftCard->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function validation_fails_for_invalid_gift_card_data()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $invalidData = [
            'gift_type' => 'invalid_type',
            'amount' => -10,
            'currency' => 'INVALID',
            'background_color' => 'invalid_color',
            'recipient_email' => 'invalid_email',
        ];

        $response = $this->postJson('/api/v3.1/user/gift-cards', $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'gift_type',
                'amount',
                'currency',
                'background_color',
                'recipient_email',
                'recipient_name', // Required field
            ]);
    }

    /** @test */
    public function validation_fails_for_order_type_without_product_or_offer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $invalidData = [
            'gift_type' => 'order',
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
            // Missing product_id, offer_id, and vendor_id
        ];

        $response = $this->postJson('/api/v3.1/user/gift-cards', $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'gift_type',
                'vendor_id',
            ]);
    }

    /** @test */
    public function validation_fails_for_order_type_with_both_product_and_offer()
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);
        $offer = Offer::factory()->create(['vendor_id' => $vendor->id]);

        Sanctum::actingAs($user, ['*']);

        $invalidData = [
            'gift_type' => 'order',
            'product_id' => $product->id,
            'offer_id' => $offer->id,
            'vendor_id' => $vendor->id,
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
        ];

        $response = $this->postJson('/api/v3.1/user/gift-cards', $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'gift_type',
            ]);
    }

    /** @test */
    public function validation_fails_without_background_color_or_image()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $invalidData = [
            'gift_type' => 'wallet',
            'amount' => 100,
            'currency' => 'SAR',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
            // Missing background_color and background_image_id
        ];

        $response = $this->postJson('/api/v3.1/user/gift-cards', $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'background',
            ]);
    }

    /** @test */
    public function validation_fails_for_new_user_without_required_fields()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $invalidData = [
            'gift_type' => 'wallet',
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
            'create_new_user' => true,
            // Missing new_user_name and new_user_phone
        ];

        $response = $this->postJson('/api/v3.1/user/gift-cards', $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'new_user_name',
                'new_user_phone',
            ]);
    }

    /** @test */
    public function validation_fails_for_new_user_with_existing_user_id()
    {
        $user = User::factory()->create();
        $existingUser = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $invalidData = [
            'gift_type' => 'wallet',
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
            'user_id' => $existingUser->id,
            'create_new_user' => true,
            'new_user_name' => 'New User',
            'new_user_phone' => '+966501234567',
        ];

        $response = $this->postJson('/api/v3.1/user/gift-cards', $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'user_management',
            ]);
    }

    /** @test */
    public function user_can_redeem_wallet_gift_card()
    {
        $user = User::factory()->create();
        $giftCard = GiftCard::factory()->create([
            'sender_id' => $user->id,
            'gift_type' => 'wallet',
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
            'status' => GiftCard::STATUS_ACTIVE,
            'redemption_method' => GiftCard::REDEMPTION_PENDING,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson("/api/v3.1/user/gift-cards/{$giftCard->id}/redeem-wallet");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => __('api.gift_card_redeemed_to_wallet'),
            ]);

        $giftCard->refresh();
        $this->assertEquals(GiftCard::STATUS_USED, $giftCard->status);
        $this->assertEquals(GiftCard::REDEMPTION_WALLET, $giftCard->redemption_method);
        $this->assertNotNull($giftCard->redeemed_at);
        $this->assertNotNull($giftCard->wallet_transaction_id);
    }

    /** @test */
    public function user_cannot_redeem_already_used_gift_card()
    {
        $user = User::factory()->create();
        $giftCard = GiftCard::factory()->create([
            'sender_id' => $user->id,
            'gift_type' => 'wallet',
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
            'status' => GiftCard::STATUS_USED,
            'redemption_method' => GiftCard::REDEMPTION_WALLET,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson("/api/v3.1/user/gift-cards/{$giftCard->id}/redeem-wallet");

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => __('api.gift_card_cannot_be_redeemed'),
            ]);
    }

    /** @test */
    public function user_cannot_redeem_order_type_gift_card_to_wallet()
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);

        $giftCard = GiftCard::factory()->create([
            'sender_id' => $user->id,
            'gift_type' => 'order',
            'product_id' => $product->id,
            'vendor_id' => $vendor->id,
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
            'status' => GiftCard::STATUS_ACTIVE,
            'redemption_method' => GiftCard::REDEMPTION_PENDING,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson("/api/v3.1/user/gift-cards/{$giftCard->id}/redeem-wallet");

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => __('api.gift_card_not_wallet_type'),
            ]);
    }

    /** @test */
    public function user_can_create_order_from_gift_card()
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);

        $giftCard = GiftCard::factory()->create([
            'sender_id' => $user->id,
            'gift_type' => 'order',
            'product_id' => $product->id,
            'vendor_id' => $vendor->id,
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
            'status' => GiftCard::STATUS_ACTIVE,
            'redemption_method' => GiftCard::REDEMPTION_PENDING,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson("/api/v3.1/user/gift-cards/{$giftCard->id}/create-order");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => __('api.order_created_from_gift_card'),
            ]);

        $giftCard->refresh();
        $this->assertEquals(GiftCard::STATUS_USED, $giftCard->status);
        $this->assertEquals(GiftCard::REDEMPTION_ORDER, $giftCard->redemption_method);
        $this->assertNotNull($giftCard->redeemed_at);
        $this->assertNotNull($giftCard->order_id);
    }

    /** @test */
    public function user_cannot_create_order_from_wallet_type_gift_card()
    {
        $user = User::factory()->create();
        $giftCard = GiftCard::factory()->create([
            'sender_id' => $user->id,
            'gift_type' => 'wallet',
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
            'status' => GiftCard::STATUS_ACTIVE,
            'redemption_method' => GiftCard::REDEMPTION_PENDING,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson("/api/v3.1/user/gift-cards/{$giftCard->id}/create-order");

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => __('api.gift_card_not_order_type'),
            ]);
    }

    /** @test */
    public function user_can_cancel_order_and_redeem_to_wallet()
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);

        $giftCard = GiftCard::factory()->create([
            'sender_id' => $user->id,
            'gift_type' => 'order',
            'product_id' => $product->id,
            'vendor_id' => $vendor->id,
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
            'status' => GiftCard::STATUS_USED,
            'redemption_method' => GiftCard::REDEMPTION_ORDER,
            'order_id' => 1, // Mock order ID
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson("/api/v3.1/user/gift-cards/{$giftCard->id}/cancel-order-redeem-wallet");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => __('api.order_cancelled_and_redeemed_to_wallet'),
            ]);

        $giftCard->refresh();
        $this->assertEquals(GiftCard::REDEMPTION_WALLET, $giftCard->redemption_method);
        $this->assertNotNull($giftCard->wallet_transaction_id);
    }

    /** @test */
    public function user_cannot_cancel_order_without_existing_order()
    {
        $user = User::factory()->create();
        $giftCard = GiftCard::factory()->create([
            'sender_id' => $user->id,
            'gift_type' => 'order',
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
            'status' => GiftCard::STATUS_ACTIVE,
            'redemption_method' => GiftCard::REDEMPTION_PENDING,
            'order_id' => null,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson("/api/v3.1/user/gift-cards/{$giftCard->id}/cancel-order-redeem-wallet");

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => __('api.no_order_found'),
            ]);
    }

    /** @test */
    public function user_cannot_cancel_order_for_wallet_redemption()
    {
        $user = User::factory()->create();
        $giftCard = GiftCard::factory()->create([
            'sender_id' => $user->id,
            'gift_type' => 'wallet',
            'amount' => 100,
            'currency' => 'SAR',
            'background_color' => '#FF8B85',
            'message' => 'Test gift card',
            'recipient_name' => 'Test Recipient',
            'recipient_email' => 'test@example.com',
            'status' => GiftCard::STATUS_USED,
            'redemption_method' => GiftCard::REDEMPTION_WALLET,
            'order_id' => 1, // Mock order ID
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson("/api/v3.1/user/gift-cards/{$giftCard->id}/cancel-order-redeem-wallet");

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => __('api.gift_card_not_redeemed_as_order'),
            ]);
    }

}