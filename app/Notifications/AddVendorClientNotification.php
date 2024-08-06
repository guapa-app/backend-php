<?php

namespace App\Notifications;

use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddVendorClientNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $vendor;
    protected $isNewClient;

    public function __construct($vendor, $isNewClient)
    {
        $this->vendor = $vendor;
        $this->isNewClient = $isNewClient;
    }

    public function via($notifiable)
    {
        if ($this->isNewClient) {
            return ['whatsapp'];
        } else {
            return ['database', 'fcm'];
        }
    }

    public function toFcm($notifiable): FcmMessage
    {
        $message = new FcmMessage();
        $message->content([
            'title'        => 'تمت إضافتك كعميل',
            'body'         => $this->getSummary(),
            'sound'        => 'default', // Optional
            'icon'         => '', // Optional
            'click_action' => '', // Optional
        ]);

        return $message;
    }
    public function toWhatsApp($notifiable)
    {

    }

    public function getSummary()
    {
        return " تمت إضافتك كعميل إلى {$this->vendor->name}";
    }
}
