<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ReviewPostSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $users = User::all();
        $vendors = Vendor::all();
        $categories = \App\Models\Taxonomy::all(); // Adjust based on your category model

        if ($users->isEmpty() || $vendors->isEmpty()) {
            echo "No users or vendors found. Please seed users and vendors first.\n";
            return;
        }

        if ($categories->isEmpty()) {
            echo "No categories found. Please seed categories first.\n";
            return;
        }

        $numberOfReviews = 50;

        for ($i = 0; $i < $numberOfReviews; $i++) {
            $user = $users->random();
            $vendor = $vendors->random();
            $category = $categories->random();

            Post::create([
                'user_id' => $user->id,
                'vendor_id' => $vendor->id,
                'vendor_name' => $vendor->name,
                'type' => 'review',
                'title' => $faker->sentence(5),
                'content' => $faker->paragraph(3),
                'stars' => $faker->numberBetween(1, 5),
                'status' => 1,
                'country_id' => $vendor->country_id ?? 1,
                'show_user' => $faker->boolean(),
                'service_date' => $faker->dateTimeThisYear(),
                'category_id' => $category->id, // Add random category_id
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "Successfully seeded {$numberOfReviews} review posts.\n";
    }
}