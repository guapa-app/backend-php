<?php

namespace App\Filament\Admin\Resources\Shop\OrderResource\Actions;

use App\Enums\OrderStatus;
use App\Notifications\PendingOrderReminderNotification;
use Filament\Tables\Actions\Action;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

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
            ->modalSubmitActionLabel('Send Reminder')
            ->action(function () {
                $order = $this->getRecord();

                try {
                    $order->load('user');
                    if (!$order->user) {
                        throw new \Exception('No user associated with this order.');
                    }

                    if ($order->status == OrderStatus::Pending) {

                        $notification = new PendingOrderReminderNotification(
                            $order,
                            ['whatsapp']
                        );

                        $order->user->notify($notification);
                        $order->update(['last_reminder_sent' => now()]);

                        Notification::make()
                            ->title('Success')
                            ->body('WhatsApp reminder sent successfully!')
                            ->success()
                            ->send();
                    } else {
                        throw new \Exception('Order is not in pending status.');
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send WhatsApp reminder', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);

                    Notification::make()
                        ->title('Error')
                        ->body('Failed to send WhatsApp reminder: ' . $e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }
}
