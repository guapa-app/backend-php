<?php

namespace App\Notifications;

use App\Enums\ProductType;
use App\Models\Product;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ProductNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Product
     */
    private $product;
    private $productType;

    /**
     * @param  Product  $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->productType = $product->type->value;
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
            'database', 'fcm',
        ];
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->product->id,
            'summary' => $this->getSummary(),
            'type' => "new-$this->productType",
            'title' => "New $this->productType",
        ];
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
            'title' => $this->product->title,
            'body' => $this->getSummary(),
            'sound' => 'default', // Optional
            'icon' => '', // Optional
            'click_action' => '', // Optional
        ])->data([
            'type' => "new-$this->productType",
            'summary' => $this->getSummary(),
            'product_id' => $this->product->id,
        ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.

        return $message;
    }

    public function getSummary()
    {
        $type = $this->productType === ProductType::Product ? 'منتج' : 'إجراء';

        return 'تم إضافة '.$type.' جديد بواسطة '.$this->product->vendor->name;
    }
}
