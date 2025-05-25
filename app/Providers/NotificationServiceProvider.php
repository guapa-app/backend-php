<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use App\Channels\UnifiedNotificationChannel;
use App\Services\NotificationInterceptor;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register our custom services
        $this->app->singleton(NotificationInterceptor::class);
        $this->app->singleton(UnifiedNotificationChannel::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register the unified notification channel
        $this->app->make(ChannelManager::class)->extend('unified', function ($app) {
            return $app->make(UnifiedNotificationChannel::class);
        });

        // Optional: Override default notification behavior
        // Uncomment the line below to automatically route ALL notifications through the unified service
        // $this->overrideNotificationSystem();
    }

    /**
     * Override Laravel's notification system to automatically use unified service
     */
    protected function overrideNotificationSystem(): void
    {
        // Override the notification manager to automatically add unified channel
        $this->app->extend('notification', function ($notificationManager, $app) {
            // Add unified channel as default for all notifications
            $originalSend = $notificationManager->send(...);

            $notificationManager->macro('send', function ($notifiables, $notification) use ($app, $originalSend) {
                $unifiedChannel = $app->make(UnifiedNotificationChannel::class);
                return $unifiedChannel->send($notifiables, $notification);
            });

            return $notificationManager;
        });
    }
}
