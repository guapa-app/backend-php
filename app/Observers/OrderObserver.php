<?php

namespace App\Observers;

use App\Helpers\Common;
use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "creating" event.
     *
     * @param Order $order
     * @return void
     */
    public function creating(Order $order)
    {
        $order->hash_id = Common::generateUniqueHashForModel(Order::class, 16);
    }
}
