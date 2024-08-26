<?php

namespace App\Providers;

use App\Events;
use App\Listeners;
use App\Models;
use App\Observers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Permission;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        Events\ProductCreated::class => [
            Listeners\ProductCreatedListener::class,
        ],

        Events\OfferCreated::class => [
            Listeners\OfferCreatedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Permission::observe(Observers\PermissionObserver::class);
        Models\User::observe(Observers\UserObserver::class);
        Models\Offer::observe(Observers\OfferObserver::class);
        Models\Order::observe(Observers\OrderObserver::class);
        Models\Product::observe(Observers\ProductObserver::class);
        Models\WorkDay::observe(Observers\WorkDayObserver::class);
        Models\Setting::observe(Observers\SettingObserver::class);
        Models\SupportMessage::observe(Observers\SupportMessageObserver::class);
        Models\ShareLink::observe(Observers\ShareLinkObserver::class);
    }
}
