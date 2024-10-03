<?php

namespace App\Notifications;

use App\Channels\FirebaseChannel;
use App\Models\Order;
use App\Models\User;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The user who made the order.
     *
     * @var User
     */
    public $user;

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
        $this->user = $order->user;
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
        return [FirebaseChannel::class, 'database', 'mail'];
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->order->id,
            'summary' => $this->getSummary(),
            'type' => $this->orderType(),
            'title' => 'New Order',
            'image' => '',
        ];
    }

    public function toWhatsapp($notifiable): array
    {
        return [
            'message' => $this->getWhatsappMessage(),
            'phones' => [$notifiable->phone],
        ];
    }

    public function toMail($notifiable)
    {
        $recipientType = $this->getRecipientType($notifiable);
        $mailMessage = (new MailMessage)
            ->from('info@guapa.com.sa', 'Guapa')
            ->subject('تأكيد استلام الدفع - ' .  $this->order->id)
            ->view(
                'emails.order_confirmation',
                [
                    'order' => $this->order,
                    'recipientType' => $recipientType,
                ]
            );

        if ($recipientType == 'customer') {
            $mailMessage->attach($this->order->invoice_url, [
                'as' => 'invoice.pdf',
                'mime' => 'application/pdf',
            ]);
        }
        return $mailMessage;
    }

    public function getWhatsappMessage(): string
    {
        $this->order->loadMissing('items.product');

        $message = "------------------------\n".
            "فريق قوابا يشعركم بوجود طلب جديد ارجو التحقق من مركز الطلبات في التطبيق\n".
            "------------------------\n".
            'نوع الطلب: '.$this->orderType()."\n".
            'الاسم: '.$this->user->name."\n".
            'الرقم: '.$this->user->phone."\n";

        if ($this->orderType() == 'new-order') {
            $message .= "------------------------\n".
                "المنتجات: \n";
            foreach ($this->order->items as $item) {
                $message .= $item->product->name.' - '.$item->quantity.'-'.$item->amount."\n";
            }
        }

        $message .= "------------------------\n".
            'قوابا';

        return $message;
    }

    /**
     * Get fcm representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return FcmMessage
     */
    public function toFcm($notifiable): FcmMessage
    {
        $message = new FcmMessage();
        $message->content([
            'title' => 'New order',
            'body' => 'New order from '.$this->user->name.' #'.$this->order->id,
            'sound' => 'default',
            'icon' => '',
            'click_action' => '',
        ])->data([
            'type' => $this->orderType(),
            'summary' => $this->getSummary(),
            'id' => $this->order->id,
        ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.

        return $message;
    }

    public function toFirebase()
    {
        return [
            'title' => 'New order',
            'body' => 'New order from ' . $this->user->name . ' #' . $this->order->id,
        ];
    }

    public function getSummary(): string
    {
        return 'لديك طلب جديد رقم '.$this->order->id;
    }

    private function orderType(): string
    {
        $type = 'new-';
        $this->order->loadMissing('items');
        foreach ($this->order->items as $item) {
            if ($item->appointment != null) {
                return $type.'consultation';
            }
        }

        return $type.'order';
    }

    private function getRecipientType($notifiable): string
    {
        if ($notifiable instanceof User && $notifiable->id === $this->order->user_id) {
            return 'customer';
        }

        return 'vendor-admin';
    }
}
