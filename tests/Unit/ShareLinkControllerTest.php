<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ShareLink;
use App\Models\Taxonomy;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShareLinkControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $vendor;
    protected $user;

    /** @test */
    public function test_generates_a_share_link_for_a_product()
    {
        $product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
        ]);

        $response = $this->postJson(route('share.link.generate'), [
            'type' => 'product',
            'id' => $product->id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['link']);

        $shareLink = ShareLink::where('shareable_id', $product->id)
            ->where('shareable_type', Product::class)
            ->first();

        $this->assertNotNull($shareLink);
        $this->assertEquals($product->id, $shareLink->shareable_id);
        $this->assertEquals(Product::class, $shareLink->shareable_type);
        $this->assertTrue(Str::isUuid($shareLink->identifier));
    }

    /** @test */
    public function test_generates_a_share_link_for_a_vendor()
    {
        $response = $this->postJson(route('share.link.generate'), [
            'type' => 'vendor',
            'id' => $this->vendor->id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['link']);

        $shareLink = ShareLink::where('shareable_id', $this->vendor->id)
            ->where('shareable_type', Vendor::class)
            ->first();

        $this->assertNotNull($shareLink);
        $this->assertEquals($this->vendor->id, $shareLink->shareable_id);
        $this->assertEquals(Vendor::class, $shareLink->shareable_type);
        $this->assertTrue(Str::isUuid($shareLink->identifier));
    }

    /** @test */
    public function test_redirects_to_the_correct_item_based_on_identifier()
    {
        $product = Product::factory()->create();
        $identifier = Str::uuid();
        ShareLink::create([
            'identifier' => $identifier,
            'shareable_id' => $product->id,
            'shareable_type' => Product::class,
            'link' => url("/share/{$identifier}"),
        ]);

        $response = $this->get(route('share.link.redirect', $identifier));

        $response->assertStatus(302);
        $response->assertRedirect(route('products.show', ['id' => $product->id]));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        // Seed the database with data
        Artisan::call('roles:setup');

        $this->user = User::factory()->create();
        $this->vendor = Vendor::factory()->create();
        // Create Authenticated User.
        $this->actingAs($this->user, 'api');

        Taxonomy::factory()->create();

        $this->vendor->users()->create([
            'user_id' => auth()->id(),
            'role' => 'manager',
            'email' => auth()->user()->email,
        ]);

        auth()->user()->assignRole('manager');
    }
}
