<?php

namespace App\Observers;

use App\Events\OfferCreated;
use App\Models\Offer;

class OfferObserver
{
    /**
     * Handle the Offer "created" event.
     *
     * @param Offer $offer
     * @return void
     */
    public function created(Offer $offer)
    {
        event(new OfferCreated($offer));
    }

    /**
     * Handle the Offer "updated" event.
     *
     * @param Offer $offer
     * @return void
     */
    public function updated(Offer $offer)
    {
        //
    }

    /**
     * Handle the Offer "deleted" event.
     *
     * @param Offer $offer
     * @return void
     */
    public function deleted(Offer $offer)
    {
        //
    }

    /**
     * Handle the Offer "restored" event.
     *
     * @param Offer $offer
     * @return void
     */
    public function restored(Offer $offer)
    {
        //
    }

    /**
     * Handle the Offer "force deleted" event.
     *
     * @param Offer $offer
     * @return void
     */
    public function forceDeleted(Offer $offer)
    {
        //
    }
}
