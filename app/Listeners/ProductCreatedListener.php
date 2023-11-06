<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use App\Models\User;
use App\Notifications\ProductNotification;
use Illuminate\Support\Facades\Notification;

class ProductCreatedListener
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
     * @param \App\Events\ProductCreated $event
     * @return void
     */
    public function handle(ProductCreated $event)
    {
        $event->product->load('vendor');
        $users = User::inRandomOrder()->limit(50)->get();
        Notification::send($users, new ProductNotification($event->product));
    }
}
