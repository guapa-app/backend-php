<?php

namespace App\Notifications;

use App\Channels\FirebaseChannel;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Order object.
     *
     * @var Order
     */
    public $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
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
        return ['database', FirebaseChannel::class];
    }


    public function toArray($notifiable)
    {
        return [
            'id' => $this->order->id,
            'summary' => $this->getSummary(),
            'type' => 'new-review',
            'title' => 'تقييم جديد',
        ];
    }

    public function toFirebase()
    {
        return [
            'title' => 'تقييم جديد',
            'body' => $this->getSummary(),
        ];
    }

    public function getSummary()
    {
        return " تم تقييم الطلب رقم {$this->order->id} ";
    }
}
