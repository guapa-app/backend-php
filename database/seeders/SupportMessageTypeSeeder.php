<?php

namespace Database\Seeders;

use App\Models\SupportMessageType;
use Illuminate\Database\Seeder;

class SupportMessageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names_arr = [
            'Issue',
            'Feature Request',
            'General Inquiry',
        ];

        foreach ($names_arr as $name) {
            SupportMessageType::factory()
                ->create([
                    'name' => $name,
                ]);
        }
    }
}
