<?php

namespace App\Filament\Admin\Resources\Shop\OrderResource\Actions;

use App\Enums\OrderStatus;
use App\Notifications\PendingOrderReminderNotification;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Support\Colors\Color;

class SendWhatsAppReminderAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'send-whatsapp-reminder';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-chat-bubble-left-right')
            ->label('Send WhatsApp Reminder')
            ->color(Color::Yellow)
            ->requiresConfirmation()
            ->modalHeading('Send WhatsApp Reminder')
            ->modalDescription('Are you sure you want to send a WhatsApp reminder for this order?')
            ->modalSubmitActionLabel('Send Reminder');
    }

    public function handle(): void
    {
        $order = $this->getRecord();

        if ($order->status == OrderStatus::Pending) {
            // Send WhatsApp notification
            $order->user->notify(new PendingOrderReminderNotification(
                $order,
                ['whatsapp']
            ));

            // Update the last reminder sent timestamp
            $order->update(['last_reminder_sent' => now()]);

            Notification::make()
                ->title('WhatsApp Reminder Sent')
                ->success()
                ->send();
        }
    }
}
