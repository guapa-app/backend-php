<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'          => $this->faker->name,
            'email'         => $this->faker->email,
            'status'        => 1, // Active
            'verified'      => true,

            'phone'         => $this->faker->phoneNumber,
            'about'         => $this->faker->paragraph,
            'whatsapp'      => $this->faker->phoneNumber,
            'twitter'       => 'www.x.com',

            'instagram'     => 'www.instagram.com',
            'snapchat'      => 'www.snapchat.com',
            'type'          => array_rand(Vendor::TYPES),
            'working_days'  => 'Sunday, Tuesday, Thursday',

            'working_hours'         => '09:00 AM - 09:00 PM',
            'website_url'           => $this->faker->url,
            'known_url'             => $this->faker->url,
            'tax_number'            => $this->faker->randomNumber(9),
            'health_declaration'    => $this->faker->randomNumber(9),

            'cat_number'    => $this->faker->randomNumber(9),
            'reg_number'    => $this->faker->randomNumber(9),
        ];
    }
}
