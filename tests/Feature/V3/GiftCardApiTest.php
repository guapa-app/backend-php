<?php

declare(strict_types=1);

namespace Tests\Feature\V3;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GiftCardApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed'); // Seed the database for real data
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
}