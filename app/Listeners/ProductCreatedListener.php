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
        $vendor = $event->product->vendor;

        // Get users who favorited this vendor using the relation
        $favoritedUsers = $vendor->favoritedBy()->get();

        // Get 50 random users excluding those who already favorited
        $randomUsers = User::whereNotIn('id', $favoritedUsers->pluck('id'))
            ->inRandomOrder()
            ->limit(50)
            ->get();

        // Merge both collections while avoiding duplicates
        $usersToNotify = $favoritedUsers->concat($randomUsers)->unique('id');

        // Send notifications in chunks to avoid timeout
        $usersToNotify->chunk(100)->each(function ($chunk) use ($event) {
            Notification::send($chunk, new ProductNotification($event->product));
        });
    }
}
