<?php

namespace App\Notifications;

use App\Channels\FirebaseChannel;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductIsUnshippableNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Product $product)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [FirebaseChannel::class, 'database'];
    }

    // /**
    //  * Get the mail representation of the notification.
    //  */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->product->id,
            'summary' => $this->getSummary(),
            'type' => 'product-is-unshippable',
            'title' => 'Product Is Unshippable',
        ];
    }

    public function toFirebase()
    {
        return [
            'title' => 'Product Is Unshippable',
            'body' => $this->getSummary(),
            'data' => [
                'type' => 'cart',
                'id' => null,
            ]
        ];
    }

    public function getSummary()
    {
        return __('api.cart.product_is_unshippable', ['product_title' => $this->product->title]);
    }
}
