<?php

namespace App\Notifications;

use App\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class InvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * invoice_url
     *
     * @var mixed
     */
    private $invoice_url;

    public function __construct($invoice_url)
    {
        $this->invoice_url = $invoice_url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            WhatsAppChannel::class,
        ];
    }

    public function toWhatsapp($notifiable)
    {
        return [
            'client' => $this->formatPhoneNumber($notifiable->phone),
            'campaignName' => 'invoc',
            'variables' => [
                'name' => $notifiable->name,
                'invoce' => $this->invoice_url,
            ],
            'campaignVersion' => '0196112f-fc98-9419-0acb-dd76e57316b2',
        ];
    }


    private function formatPhoneNumber($number)
    {
        // Remove all characters except numbers and +
        $number = preg_replace('/[^\d+]/', '', $number);

        // If it doesn't start with +, add it
        if (strpos($number, '+') !== 0) {
            $number = '+' . $number;
        }

        return $number;
    }
}
