<?php

namespace App\Console\Commands;

use App\Channels\FirebaseChannel;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Setting;
use App\Notifications\PendingOrderReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendPendingOrderReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected  $signature = 'order:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders to customers who have pending orders.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Send reminders to users who have pending orders
        // Get all orders that are pending
        $intervalHours = Setting::getOrderReminderAfter();

        $orders = Order::where('status', OrderStatus::Pending)
            ->where(function ($query) use ($intervalHours) {
                $query->whereNull('last_reminder_sent')
                    ->orWhere('last_reminder_sent', '<=',
                        Carbon::now()->subHours($intervalHours));
            })
            ->get();

        // Loop through each order and send a reminder
        foreach ($orders as $order) {
            // Send a reminder to the user
            $order->user->notify(new PendingOrderReminderNotification($order , ['database',FirebaseChannel::class]));

            // Update the last reminder sent timestamp
            $order->update(['last_reminder_sent' => Carbon::now()]);

        }
    }

}
