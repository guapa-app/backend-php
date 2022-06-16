<?php

namespace App\Notifications\Channels;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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
            $this->sendMessage($this->preparePhoneNumber($phone), $message['message']);
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
            if (config('app.debug')) info('Sending message to ' . $phone);
            $data = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . config('aqwhatsapp.api_token'),
                'Accept' => 'application/json',
            ])
                ->timeout(5)
                ->post("http://whatsapp.aq-apps.xyz/api/send-message", [
                    'session_uuid' => config('aqwhatsapp.session_uuid'),
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

    private function preparePhoneNumber(string $phone): string
    {
        if (Str::startsWith($phone, '+')) {
            $phone = substr($phone, 1);
        }

        if (Str::startsWith($phone, '966')) {
            $phone = substr($phone, 3);
        }

        if (Str::startsWith($phone, '0')) {
            $phone = substr($phone, 1);
        }

        return '966' . $phone;
    }
}
