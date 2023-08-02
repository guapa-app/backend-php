<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\User;
use App\Notifications\Channels\WhatsappChannel;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderUpdatedNotification extends Notification
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
        return ['fcm', 'database', WhatsappChannel::class];
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'summary'  => $this->getSummary(),
            'type'     => $this->orderType(),
        ];
    }

    public function toWhatsapp($notifiable): array
    {
        return [
            'message' => $this->getWhatsappMessage(),
            'phones' => [$notifiable->phone],
        ];
    }

    public function getWhatsappMessage(): string
    {
        $this->order->loadMissing('vendor');

        return "------------------------\n" .
            "فريق قوابا يشعركم بوجود تحديث علي طلبكم ارجو التحقق من مركز الطلبات في التطبيق\n" .
            "------------------------\n" .
            "نوع الطلب: " . $this->orderType() . "\n" .
            "رقم الطلب: " . $this->order->id . "\n" .
            "التاجر: " . $this->order->vendor->name . "\n" .
            "------------------------\n" .
            "قوابا";
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
            'title'         => 'Update order',
            'body'          => $this->getSummary(),
            'sound'         => 'default',
            'icon'          => '',
            'click_action'  => ''
        ])->data([
            'type'          => $this->orderType(),
            'summary'       => $this->getSummary(),
            'order_id'      => $this->order->id,
        ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.

        return $message;
    }

    public function getSummary(): string
    {
        return 'تم تحديث حالة الطلب';
    }

    private function orderType(): string
    {
        $type = 'update-';
        $this->order->loadMissing('items');
        foreach ($this->order->items as $item) {
            if ($item->appointment != null) {
                return $type . 'consultation';
            }
        }
        return $type . 'order';
    }
}
