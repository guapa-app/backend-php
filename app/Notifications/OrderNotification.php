<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderNotify;
use Illuminate\Bus\Queueable;
use Benwilkins\FCM\FcmMessage;
use App\Channels\FirebaseChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Order object.
     *
     * @var OrderNotify
     */
    public $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(OrderNotify $order)
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

    public function toMail($notifiable)
    {
        $recipientType = $this->getRecipientType($notifiable);
        $mailMessage = (new MailMessage)
            ->from(env('MAIL_FROM_ADDRESS'), 'Guapa')
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

    public function toFirebase()
    {
        return [
            'title' => 'New order',
            'body' => 'New order from ' . $this->order->user->name . ' #' . $this->order->id,
        ];
    }

    public function getSummary(): string
    {
        return 'لديك طلب جديد رقم '.$this->order->id;
    }

    private function orderType(): string
    {
        $type = 'new-';
//        $this->order->loadMissing('items');
//        foreach ($this->order->items as $item) {
//            if ($item->appointment != null) {
//                return $type.'consultation';
//            }
//        }

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
