<?php

namespace App\Notifications;
use App\Channels\FirebaseChannel;
use App\Channels\WhatsAppChannel;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
class PendingOrderReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $order;
    private $channels;

    /**
     * @param Order $order
     * @param array $channels Array of channel classes to use
     */
    public function __construct(Order $order, array $channels = null)
    {
        $this->order = $order;
        $this->channels = $channels ?? [FirebaseChannel::class,'database', 'whatsapp'];
    }

    public function via($notifiable)
    {
        return $this->channels;
    }

    public function toArray($notifiable)
    {
        return [
            'id' => $this->order->id,
            'summary' => $this->getSummary(),
            'type'    => 'push-notifications',
            'title'   => 'Pending Order',
            'image'   => null,
        ];
    }

    public function toFirebase()
    {
        return [
            'title' => 'Update order',
            'body' => $this->getSummary(),
            'data' => [
                'type' => 'order',
                'id' => $this->order->id
            ]
        ];
    }

    public function toWhatsapp($notifiable)
    {
        return [
            'client' => $notifiable->phone,
            'campaignName' => 'campaign 11',
            'variables' => [
                'username' => $notifiable->name,
            ],
            "campaignVersion" => "0192b4be-a289-fbf1-d5aa-66a2622caf11"

        ];
    }
    public function getSummary(): string
    {
        return 'لديك طلب غير مكتمل . برجاء اكمال الطلب ' . $this->order->id;
    }

}
