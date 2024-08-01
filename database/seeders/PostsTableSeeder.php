<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Admin;
use App\Models\Taxonomy;

class PostsTableSeeder extends Seeder
{
    public function run()
    {
        Post::factory()->count(10)->create([
            'admin_id' => 1,
            'category_id' => 1,
        ]);
    }
}
