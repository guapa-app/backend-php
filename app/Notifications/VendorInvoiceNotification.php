<?php

namespace App\Notifications;

use App\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class VendorInvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * order
     *
     * @var mixed
     */
    private $order;

    public function __construct($order)
    {
        $this->order = $order;
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
            'campaignName' => 'service provider notification',
            'variables' => [
                'vendorname' => $notifiable->name,
                'servicesname' => $this->order->items()->first()->product->title,
                'cliendname' => $this->order->user->name
            ]
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
