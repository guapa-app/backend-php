<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UnifiedNotificationService;

class TestNotificationService extends Command
{
    protected $signature = 'notifications:test {--user-id=1 : User ID to send test notification to}';
    protected $description = 'Send a test notification through the unified service';

    public function handle()
    {
        $userId = $this->option('user-id');
        
        $this->info('🧪 Testing Notification Service');
        $this->info('==============================');
        $this->newLine();

        try {
            $unifiedService = app(UnifiedNotificationService::class);
            
            $this->line('📤 Sending test notification...');
            
            $result = $unifiedService->send(
                module: 'test-notification',
                title: 'Test Notification',
                summary: 'This is your first notification through the external service! 🎉',
                recipientId: (int)$userId,
                data: [
                    'test' => true,
                    'timestamp' => now()->toISOString(),
                    'source' => 'guapa-laravel-cli',
                    'version' => '1.0.0'
                ]
            );
            
            if ($result) {
                $this->info('   ✅ Notification sent successfully!');
                $this->comment('   📡 Your app is now sending notifications through the external service');
                $this->newLine();
                
                $this->line('📊 What happened:');
                $this->line('   1. Laravel app created notification payload');
                $this->line('   2. UnifiedNotificationService processed the request');
                $this->line('   3. NotificationChannelResolver determined the channel');
                $this->line('   4. ExternalNotificationService sent HTTP request');
                $this->line('   5. External service received and will deliver notification');
                
            } else {
                $this->error('   ❌ Notification failed to send');
                $this->line('   🔍 Check logs for details: tail -f storage/logs/laravel.log');
                $this->line('   🔧 Verify configuration: php artisan notifications:migration-status');
            }
            
        } catch (\Exception $e) {
            $this->error('   ❌ Error: ' . $e->getMessage());
            $this->newLine();
            $this->line('💡 Common solutions:');
            $this->line('   - Check if external service endpoint is accessible');
            $this->line('   - Verify authentication tokens are set correctly');
            $this->line('   - Ensure external service is running');
        }

        $this->newLine();
        $this->comment('🚀 Ready to migrate more notifications? Run: php artisan notifications:migration-status');
    }
} 