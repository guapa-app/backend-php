<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\InterServiceAuthenticationService;
use App\Services\ExternalNotificationService;
use App\Services\UnifiedNotificationService;

class TestInterServiceAuthentication extends Command
{
    protected $signature = 'notifications:test-authentication {--full : Run full authentication and communication test}';
    protected $description = 'Test inter-service authentication and communication';

    public function handle()
    {
        $this->info('🔐 Testing Inter-Service Authentication System');
        $this->info('=============================================');
        $this->newLine();

        // Test 1: Configuration Check
        $this->testConfiguration();
        $this->newLine();

        // Test 2: Authentication Service
        $this->testAuthenticationService();
        $this->newLine();

        // Test 3: External Notification Service
        $this->testExternalService();
        $this->newLine();

        if ($this->option('full')) {
            // Test 4: Full End-to-End Test
            $this->testEndToEnd();
            $this->newLine();
        }

        $this->info('🎉 Authentication testing completed!');
    }

    protected function testConfiguration()
    {
        $this->line('📋 1. Configuration Test');
        $this->line('   Checking authentication configuration...');

        $authService = app(InterServiceAuthenticationService::class);
        $config = $authService->getConfigurationStatus();

        foreach ($config as $key => $value) {
            if ($key === 'is_configured') {
                continue;
            }

            $status = $value === 'SET' || is_numeric($value) ? '✅' : '❌';
            $displayValue = is_numeric($value) ? $value : $value;
            $this->line("   {$status} {$key}: {$displayValue}");
        }

        if ($config['is_configured']) {
            $this->info('   ✅ Configuration is complete');
        } else {
            $this->error('   ❌ Configuration is incomplete');
            $this->comment('   💡 Set missing values in .env file');
        }

        return $config['is_configured'];
    }

    protected function testAuthenticationService()
    {
        $this->line('🔑 2. Authentication Service Test');

        $authService = app(InterServiceAuthenticationService::class);

        try {
            $this->line('   Testing connection to notification service...');
            $result = $authService->testConnection();

            if ($result['success']) {
                $this->info('   ✅ Authentication successful');
                $this->line("   📊 Service Status: {$result['status']}");
                $this->line("   ⏱️  Response Time: {$result['response_time']}ms");
                $this->line("   📦 Service Version: {$result['service_version']}");
            } else {
                $this->error('   ❌ Authentication failed');
                $this->line("   🔍 Error: {$result['error']}");
            }

            return $result['success'];
        } catch (\Exception $e) {
            $this->error('   ❌ Authentication service error');
            $this->line("   🔍 Error: {$e->getMessage()}");
            return false;
        }
    }

    protected function testExternalService()
    {
        $this->line('📡 3. External Notification Service Test');

        $externalService = app(ExternalNotificationService::class);

        try {
            $this->line('   Testing external service health...');
            $health = $externalService->getHealthStatus();

            $this->line("   📊 Status: {$health['status']}");
            $this->line("   💬 Message: {$health['message']}");

            if ($health['status'] === 'healthy') {
                $this->info('   ✅ External service is healthy');

                // Test configuration status
                $config = $externalService->getConfigurationStatus();
                if ($config['is_configured']) {
                    $this->line('   ✅ Service configuration verified');
                } else {
                    $this->warn('   ⚠️  Service configuration incomplete');
                }

                return true;
            } else {
                $this->error('   ❌ External service is unhealthy');
                if (isset($health['details']['error'])) {
                    $this->line("   🔍 Details: {$health['details']['error']}");
                }
                return false;
            }
        } catch (\Exception $e) {
            $this->error('   ❌ External service test failed');
            $this->line("   🔍 Error: {$e->getMessage()}");
            return false;
        }
    }

    protected function testEndToEnd()
    {
        $this->line('🚀 4. End-to-End Communication Test');

        $unifiedService = app(UnifiedNotificationService::class);

        try {
            $this->line('   Sending test notification through unified service...');

            $success = $unifiedService->send(
                module: 'test-authentication',
                title: 'Authentication Test',
                summary: 'Testing end-to-end inter-service communication',
                recipientId: 1,
                data: [
                    'test_type' => 'end_to_end',
                    'timestamp' => now()->toISOString(),
                    'source' => 'artisan_command'
                ]
            );

            if ($success) {
                $this->info('   ✅ End-to-end test successful');
                $this->line('   📨 Test notification sent successfully');
                $this->line('   🔗 Communication chain verified:');
                $this->line('      UnifiedNotificationService ✅');
                $this->line('      → ExternalNotificationService ✅');
                $this->line('      → InterServiceAuthenticationService ✅');
                $this->line('      → External Notification App ✅');
            } else {
                $this->error('   ❌ End-to-end test failed');
                $this->line('   🔍 Check logs for detailed error information');
            }

            return $success;
        } catch (\Exception $e) {
            $this->error('   ❌ End-to-end test error');
            $this->line("   🔍 Error: {$e->getMessage()}");
            return false;
        }
    }

    protected function displaySecurityFeatures()
    {
        $this->info('🛡️  Security Features Implemented:');
        $this->line('   ✅ Bearer Token Authentication');
        $this->line('   ✅ HMAC-SHA256 Request Signing');
        $this->line('   ✅ Timestamp Validation (5min window)');
        $this->line('   ✅ Cryptographic Nonce Protection');
        $this->line('   ✅ Replay Attack Prevention');
        $this->line('   ✅ Request Integrity Verification');
        $this->line('   ✅ Secure Error Handling');
        $this->line('   ✅ Comprehensive Logging');
    }
}
