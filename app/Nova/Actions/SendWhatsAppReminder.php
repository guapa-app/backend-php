<?php

namespace App\Nova\Actions;

use App\Channels\WhatsAppChannel;
use App\Enums\OrderStatus;
use App\Notifications\PendingOrderReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class SendWhatsAppReminder extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Send WhatsApp Reminder';

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $order) {
            if ($order->status ==  OrderStatus::Pending) {
                // Only send WhatsApp notification
                $order->user->notify(new PendingOrderReminderNotification(
                    $order,
                    ['whatsapp']
                ));

                // Update the last reminder sent timestamp
                $order->update(['last_reminder_sent' => now()]);
            }
        }

        return Action::message('WhatsApp reminders sent successfully!');
    }

}
