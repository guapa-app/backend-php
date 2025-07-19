<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GiftCardSetting;

class GiftCardSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GiftCardSetting::initializeDefaults();
    }
}
