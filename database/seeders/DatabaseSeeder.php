<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationSetting;
use App\Enums\NotificationTypeEnum;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        // Seed default notification settings for all modules (including sms-otp)
        $defaults = [
            'new-order' => 'firebase',
            'new-offer' => 'whatsapp',
            'message' => 'in_app',
            'sms-otp' => 'sms',
        ];
        foreach ($defaults as $module => $channel) {
            \App\Models\NotificationSetting::firstOrCreate([
                'notification_module' => $module,
                'admin_id' => null,
            ], [
                'channels' => $channel,
                'created_by_super_admin' => true,
            ]);
        }
    }
}
