<?php

namespace App\Providers;

use App\Events\OfferCreated;
use App\Events\ProductCreated;
use App\Listeners\OfferCreatedListener;
use App\Listeners\ProductCreatedListener;
use App\Models\Offer;
use App\Models\Product;
use App\Models\User;
use App\Models\WorkDay;
use App\Observers\OfferObserver;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use App\Observers\WorkDayObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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

        ProductCreated::class => [
            ProductCreatedListener::class,
        ],

        OfferCreated::class => [
            OfferCreatedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Offer::observe(OfferObserver::class);
        Product::observe(ProductObserver::class);
        WorkDay::observe(WorkDayObserver::class);
    }
}
