<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NotificationSetting;

class NotificationSettingsSeeder extends Seeder
{
    /**
     * Seed default notification settings for all modules
     */
    public function run(): void
    {
        $defaultSettings = [
            // Core modules
            'new-order' => 'firebase',
            'new-offer' => 'firebase', 
            'message' => 'in_app',
            'sms-otp' => 'sms',
            
            // Community modules
            'comments' => 'in_app',
            'community' => 'in_app',
            'new-review' => 'in_app',
            'new-like' => 'in_app',
            
            // Support modules
            'user-ticket' => 'in_app',
            'support-message' => 'in_app',
            
            // System modules
            'general' => 'in_app',
            'push-notifications' => 'firebase',
            
            // Product modules
            'new-product' => 'firebase',
            'new-procedure' => 'firebase',
            
            // Order updates
            'update-order' => 'firebase',
            'update-consultation' => 'firebase',
        ];

        foreach ($defaultSettings as $module => $channel) {
            NotificationSetting::firstOrCreate([
                'notification_module' => $module,
                'admin_id' => null, // Global setting
            ], [
                'channels' => $channel,
                'created_by_super_admin' => true,
            ]);
        }

        $this->command->info('Default notification settings seeded successfully!');
    }
}
