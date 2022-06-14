<?php

namespace App\Listeners;

use App\Events\OfferCreated;
use App\Models\User;
use App\Notifications\OfferNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class OfferCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param \App\Events\OfferCreated $event
     * @return void
     */
    public function handle(OfferCreated $event)
    {
        $event->offer->load('product', 'product.vendor');
        $users = User::inRandomOrder()->limit(200)->get();
        Notification::send($users, new OfferNotification($event->offer));
    }
}
