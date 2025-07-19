<?php

namespace Database\Factories;

use App\Models\GiftCardBackground;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GiftCardBackgroundFactory extends Factory
{
    protected $model = GiftCardBackground::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'is_active' => true,
            'uploaded_by' => User::factory(),
        ];
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }
}
