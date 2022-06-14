<?php

namespace App\Notifications\Channels;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class WhatsappChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toWhatsapp($notifiable);

        foreach ($message['phones'] as $phone) {
            $this->sendMessage($phone, $message['message']);
        }
    }

    /**
     * Send message to whatsapp
     *
     * @returns bool
     */
    public function sendMessage($phone, $message): bool
    {
        try {
            $data = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer 3|CMgNF8eZYdUoKQ2dcb9FtBthImeXEPOZoKEC2sV4',
            ])
                ->timeout(5)
                ->post("http://whatsapp.aq-apps.xyz/api/send-message", [
                    'session_uuid' => '96852687-2f24-417b-afca-83d5771aabfc',
                    'session_token' => '$2b$10$BXfKoLMQaSGKW8oZond5w.wA_iwABxeIWJeUooglwp3GEYUEMR412',
                    'phone' => $phone,
                    'message' => $message,
                    'schedule_at' => now(),
                ]);
            if (config('app.debug')) info($data->body());
            return true;
        } catch (Exception $e) {
            if (config('app.debug')) info($e->getMessage());
            unset($e);
            return false;
        }
    }
}
