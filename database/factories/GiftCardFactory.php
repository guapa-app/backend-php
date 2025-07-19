<?php

namespace Database\Factories;

use App\Models\GiftCard;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Offer;
use App\Models\GiftCardBackground;
use Illuminate\Database\Eloquent\Factories\Factory;

class GiftCardFactory extends Factory
{
    protected $model = GiftCard::class;

    public function definition()
    {
        return [
            'code' => 'GC' . strtoupper(uniqid()),
            'sender_id' => User::factory(),
            'user_id' => User::factory(),
            'vendor_id' => Vendor::factory(),
            'gift_type' => $this->faker->randomElement([GiftCard::GIFT_TYPE_WALLET, GiftCard::GIFT_TYPE_ORDER]),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'currency' => 'SAR',
            'status' => GiftCard::STATUS_ACTIVE,
            'redemption_method' => GiftCard::REDEMPTION_PENDING,
            'background_color' => $this->faker->hexColor(),
            'message' => $this->faker->sentence(),
            'recipient_name' => $this->faker->name(),
            'recipient_email' => $this->faker->email(),
            'recipient_number' => $this->faker->phoneNumber(),
            'expires_at' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }

    public function walletType()
    {
        return $this->state(function (array $attributes) {
            return [
                'gift_type' => GiftCard::GIFT_TYPE_WALLET,
                'product_id' => null,
                'offer_id' => null,
                'vendor_id' => null,
            ];
        });
    }

    public function orderType()
    {
        return $this->state(function (array $attributes) {
            return [
                'gift_type' => GiftCard::GIFT_TYPE_ORDER,
                'product_id' => Product::factory(),
                'vendor_id' => Vendor::factory(),
            ];
        });
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => GiftCard::STATUS_ACTIVE,
            ];
        });
    }

    public function used()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => GiftCard::STATUS_USED,
                'redeemed_at' => now(),
                'redemption_method' => $this->faker->randomElement([GiftCard::REDEMPTION_WALLET, GiftCard::REDEMPTION_ORDER]),
            ];
        });
    }

    public function expired()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => GiftCard::STATUS_EXPIRED,
                'expires_at' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
            ];
        });
    }

    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => GiftCard::STATUS_CANCELLED,
            ];
        });
    }

    public function withBackgroundImage()
    {
        return $this->state(function (array $attributes) {
            return [
                'background_image_id' => GiftCardBackground::factory(),
                'background_color' => null,
            ];
        });
    }
}
