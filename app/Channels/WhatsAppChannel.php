<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Contracts\WhatsAppServiceInterface;

class WhatsAppChannel
{
    protected $whatsappService;

    public function __construct(WhatsAppServiceInterface $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function send($notifiable, Notification $notification)
    {
        if ($notifiable instanceof \Illuminate\Support\Collection) {
            $entries = $notifiable->map(function ($user) use ($notification) {
                return $notification->toWhatsapp($user);
            })->toArray();
        } else {
            $entries = [$notification->toWhatsapp($notifiable)];
        }

        \Log::info('Sending WhatsApp campaign channel :', $entries);

        return $this->whatsappService->sendCampaign($entries);
    }
}
