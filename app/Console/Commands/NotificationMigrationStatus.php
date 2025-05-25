<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ExternalNotificationService;
use App\Services\NotificationAuthService;

class NotificationMigrationStatus extends Command
{
    protected $signature = 'notifications:migration-status';
    protected $description = 'Check the status of notification system migration to external service';

    public function handle()
    {
        $this->info('🔄 Notification System Migration Status');
        $this->info('=====================================');
        $this->newLine();

        // Check configuration
        $this->checkConfiguration();
        $this->newLine();

        // Check service health
        $this->checkServiceHealth();
        $this->newLine();

        // Show migration progress
        $this->showMigrationProgress();
        $this->newLine();

        // Show next steps
        $this->showNextSteps();
    }

    protected function checkConfiguration()
    {
        $this->info('🔧 Configuration Status:');

        $configs = [
            'EXTERNAL_NOTIFICATION_ENDPOINT' => config('services.external_notification.endpoint'),
            'EXTERNAL_NOTIFICATION_TOKEN' => config('services.external_notification.token') ? '***SET***' : 'NOT SET',
            'EXTERNAL_NOTIFICATION_APP_ID' => config('services.external_notification.app_id'),
            'EXTERNAL_NOTIFICATION_SECRET_KEY' => config('services.external_notification.secret_key') ? '***SET***' : 'NOT SET',
        ];

        foreach ($configs as $key => $value) {
            $status = ($value && $value !== 'NOT SET') ? '✅' : '❌';
            $this->line("   {$status} {$key}: " . ($value ?: 'NOT SET'));
        }
    }

    protected function checkServiceHealth()
    {
        $this->info('🏥 Service Health:');

        try {
            $authService = app(NotificationAuthService::class);
            $errors = $authService->validateConfiguration();

            if (empty($errors)) {
                $this->line('   ✅ Configuration is valid');

                $externalService = app(ExternalNotificationService::class);
                $healthStatus = $externalService->getHealthStatus();

                if ($healthStatus['configured']) {
                    $this->line('   ✅ External service is configured');
                } else {
                    $this->line('   ❌ External service configuration has issues');
                    foreach ($healthStatus['configuration_errors'] as $error) {
                        $this->line("     - {$error}");
                    }
                }
            } else {
                $this->line('   ❌ Configuration errors:');
                foreach ($errors as $error) {
                    $this->line("     - {$error}");
                }
            }
        } catch (\Exception $e) {
            $this->error('   ❌ Error checking service health: ' . $e->getMessage());
        }
    }

    protected function showMigrationProgress()
    {
        $this->info('📊 Migration Progress:');

        $completed = [
            'app/Services/V3_1/OrderPaymentService.php' => 'Order payments & invoices',
            'app/Services/MessagingService.php' => 'Chat messages & offers',
            'app/Observers/CommentObserver.php' => 'Comment notifications',
            'app/Repositories/Eloquent/DatabaseNotificationRepository.php' => 'Push notifications',
        ];

        $remaining = [
            'app/Services/V3_1/OrderService.php' => 'Order creation & updates',
            'app/Services/ConsultationService.php' => 'Consultation management',
            'app/Jobs/ProcessVendorPayouts.php' => 'Financial notifications',
            'app/Listeners/OfferCreatedListener.php' => 'Bulk offer notifications',
            'app/Listeners/ProductCreatedListener.php' => 'Bulk product notifications',
            'app/Http/Controllers/Api/FavoriteController.php' => 'Social interactions',
        ];

        $this->line('   ✅ Completed migrations:');
        foreach ($completed as $file => $description) {
            $this->line("     - {$description}");
        }

        $this->newLine();
        $this->line('   ❌ Remaining migrations:');
        foreach ($remaining as $file => $description) {
            $this->line("     - {$description} ({$file})");
        }

        $total = count($completed) + count($remaining);
        $completedCount = count($completed);
        $percentage = round(($completedCount / $total) * 100);

        $this->newLine();
        $this->line("   📈 Progress: {$completedCount}/{$total} files ({$percentage}%)");
    }

    protected function showNextSteps()
    {
        $this->info('🚀 Next Steps:');

        $steps = [
            'Set up external notification service endpoint in .env',
            'Configure authentication tokens (EXTERNAL_NOTIFICATION_TOKEN)',
            'Test connection: php artisan notifications:test-connection',
            'Migrate remaining high-priority files',
            'Monitor logs: tail -f storage/logs/laravel.log',
            'Check health: GET /api/vendor/v3.1/notifications/notifications/health/status'
        ];

        foreach ($steps as $index => $step) {
            $this->line('   ' . ($index + 1) . '. ' . $step);
        }

        $this->newLine();
        $this->comment('💡 All new notifications are automatically routed through the external service!');
        $this->comment('   Your app no longer sends notifications directly - everything goes through the unified service.');
    }
}
