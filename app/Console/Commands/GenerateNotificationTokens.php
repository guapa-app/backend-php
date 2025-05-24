<?php

namespace App\Console\Commands;

use App\Services\NotificationAuthService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateNotificationTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:generate-tokens 
                            {--update-env : Update .env file with generated tokens}
                            {--show-config : Show example configuration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate secure authentication tokens for external notification service';

    protected $authService;

    public function __construct(NotificationAuthService $authService)
    {
        parent::__construct();
        $this->authService = $authService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔐 Generating Notification Authentication Tokens');
        $this->line('');

        // Generate tokens
        $apiToken = $this->authService->generateApiToken();
        $secretKey = $this->authService->generateSecretKey();
        $appId = 'guapa-laravel-' . now()->format('Ymd');

        // Display generated tokens
        $this->info('Generated Tokens:');
        $this->line('');
        $this->line("API Token: <comment>{$apiToken}</comment>");
        $this->line("Secret Key: <comment>{$secretKey}</comment>");
        $this->line("App ID: <comment>{$appId}</comment>");
        $this->line('');

        // Show environment variables
        $this->info('Add these to your .env file:');
        $this->line('');
        $envVars = [
            'EXTERNAL_NOTIFICATION_TOKEN' => $apiToken,
            'EXTERNAL_NOTIFICATION_SECRET_KEY' => $secretKey,
            'EXTERNAL_NOTIFICATION_APP_ID' => $appId,
            'EXTERNAL_NOTIFICATION_ENDPOINT' => 'https://your-notification-service.com/api/notifications',
            'EXTERNAL_NOTIFICATION_TIMEOUT' => '30',
            'EXTERNAL_NOTIFICATION_RETRY_ATTEMPTS' => '3',
            'EXTERNAL_NOTIFICATION_RETRY_DELAY' => '1000',
            'EXTERNAL_NOTIFICATION_VERIFY_SSL' => 'true'
        ];

        foreach ($envVars as $key => $value) {
            $this->line("<info>{$key}</info>=<comment>{$value}</comment>");
        }

        // Update .env file if requested
        if ($this->option('update-env')) {
            $this->updateEnvFile($envVars);
        }

        // Show configuration example if requested
        if ($this->option('show-config')) {
            $this->showConfigExample($apiToken, $secretKey, $appId);
        }

        $this->line('');
        $this->info('✅ Tokens generated successfully!');
        $this->line('');
        $this->warn('⚠️  Keep these tokens secure and share them only with your external notification service.');

        return 0;
    }

    /**
     * Update .env file with generated tokens
     *
     * @param array $envVars
     * @return void
     */
    protected function updateEnvFile(array $envVars): void
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            $this->error('.env file not found. Please create it first.');
            return;
        }

        $envContent = File::get($envPath);

        foreach ($envVars as $key => $value) {
            if (strpos($envContent, $key) !== false) {
                // Update existing variable
                $envContent = preg_replace(
                    "/^{$key}=.*$/m",
                    "{$key}={$value}",
                    $envContent
                );
            } else {
                // Add new variable
                $envContent .= "\n{$key}={$value}";
            }
        }

        File::put($envPath, $envContent);
        $this->info('✅ .env file updated with new tokens');
    }

    /**
     * Show configuration example for external service
     *
     * @param string $apiToken
     * @param string $secretKey
     * @param string $appId
     * @return void
     */
    protected function showConfigExample(string $apiToken, string $secretKey, string $appId): void
    {
        $this->line('');
        $this->info('📋 Configuration for External Notification Service:');
        $this->line('');

        $externalConfig = [
            'allowed_apps' => [
                $appId => [
                    'name' => 'Guapa Laravel App',
                    'token' => $apiToken,
                    'secret_key' => $secretKey,
                    'callback_url' => config('app.url') . '/api/external-notifications'
                ]
            ]
        ];

        $this->line('<comment>JSON Configuration:</comment>');
        $this->line(json_encode($externalConfig, JSON_PRETTY_PRINT));

        $this->line('');
        $this->info('📡 Test Connection:');
        $this->line('Use this endpoint to test the connection:');
        $this->line('<comment>POST ' . config('app.url') . '/api/external-notifications/test</comment>');
        $this->line('');

        $this->info('🔗 Webhook Endpoints:');
        $this->line('Status updates: <comment>' . config('app.url') . '/api/external-notifications/status</comment>');
        $this->line('General webhooks: <comment>' . config('app.url') . '/api/external-notifications/webhook</comment>');
    }
}
