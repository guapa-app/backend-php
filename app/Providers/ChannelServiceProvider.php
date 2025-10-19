<?php

namespace App\Providers;

use App\Channels\FirebaseChannel;
use App\Channels\WhatsAppChannel;
use App\Contracts\WhatsAppServiceInterface;
use App\Services\ConnectlyWhatsAppService;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class ChannelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(WhatsAppServiceInterface::class, ConnectlyWhatsAppService::class);

        // Register the FirebaseChannel with dependency injection for FirebaseService
        $this->app->singleton(FirebaseChannel::class, function ($app) {
            return new FirebaseChannel($app->make(FirebaseService::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Notification::extend('whatsapp', function ($app) {
            return $app->make(WhatsAppChannel::class);
        });
    }
}
