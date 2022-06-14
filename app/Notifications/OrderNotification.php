<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\User;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification
{
    use Queueable;

    /**
     * The user who made the order
     * @var User
     */
    public $user;

    /**
     * Order object
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
        $this->user = $order->user;
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['fcm', 'database'];
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'summary' => $this->getSummary(),
            'type' => 'new-order',
        ];
    }

    /**
     * Get fcm representation of the notification
     *
     * @param mixed $notifiable
     *
     * @return FcmMessage
     */
    public function toFcm($notifiable): FcmMessage
    {
        $message = new FcmMessage();
        $message->content([
            'title' => 'New order',
            'body' => 'New order from ' . $this->user->name . ' #' . $this->order->id,
            'sound' => 'default',
            'icon' => '',
            'click_action' => ''
        ])->data([
            'type' => 'new-order',
            'summary' => $this->getSummary(),
            'order_id' => $this->order->id,
        ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.

        return $message;
    }

    public function getSummary(): string
    {
        return 'لديك طلب جديد رقم ' . $this->order->id;
    }
}
