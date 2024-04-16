<?php

namespace App\Observers;

use App\Enums\OrderStatus;
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
        $order->status = OrderStatus::Pending;
        $order->is_used = false;
    }
}
