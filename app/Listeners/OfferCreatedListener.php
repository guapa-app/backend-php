<?php

namespace App\Listeners;

use App\Events\OfferCreated;
use App\Models\User;
use App\Notifications\OfferNotification;
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
        $vendor = $event->offer->product->vendor;

        // Get users who favorited this vendor using the relation
        $favoritedUsers = $vendor->favoritedBy()->get();

        // Get 50 random users excluding those who already favorited
        $randomUsers = User::whereNotIn('id', $favoritedUsers->pluck('id'))
            ->inRandomOrder()
            ->limit(200)
            ->get();

        // Merge both collections while avoiding duplicates
        $usersToNotify = $favoritedUsers->concat($randomUsers)->unique('id');

        // Send notifications in chunks to avoid timeout
        $usersToNotify->chunk(100)->each(function ($chunk) use ($event) {
            Notification::send($chunk, new OfferNotification($event->offer));
        });
    }
}
