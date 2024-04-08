<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Enums\ProductType;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OrderExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change Orders Status to Expired when service (product) depending on expires at date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Order expire job start successfully');

        Order::query()
            ->whereNotIn('status', OrderStatus::notAvailableForExpire())
            ->chunkMap(function ($order) {
                foreach ($order->items as $item) {
                    if ($item->product?->type == ProductType::Service && $item->product?->expires_at <= today()) {
                        // Update order status to "expired"
                        $order->status = OrderStatus::Expired;
                        $order->save();
                        break; // No need to continue checking other items in this order
                    }
                }
            }, 50);
            
        Log::info('Order expire job done.');
    }
}
