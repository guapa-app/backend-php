<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Enums\ProductType;
use App\Models\Order;
use App\Models\Setting;
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
    protected $description = 'Automatically expire orders (services) that have exceeded a predefined number of days. The threshold for expiration will be configurable within the admin panel settings.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Order expire job start successfully');

        $num_of_days = Setting::getSeviceExpiredAfter();

        Order::query()
            ->whereNotIn('status', OrderStatus::notAvailableForExpire())
            ->chunkMap(function ($order) use ($num_of_days) {
                foreach ($order->items as $item) {
                    if ($order->created_at->addDays($num_of_days) <= today()) {
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
