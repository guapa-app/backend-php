<?php

namespace Database\Factories;

use App\Enums\ProductReview;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return  [
            'vendor_id'         => Vendor::factory(),
            'title'             => $this->faker->sentence,
            'description'       => $this->faker->paragraph,
            'price'             => $this->faker->randomFloat(2, 10, 1000),
            'status'            => ProductStatus::Published,
            'review'            => ProductReview::Approved,
            'type'              => ProductType::Product,
            'terms'             => $this->faker->sentence,
            'url'               => $this->faker->url,
        ];
    }
}
