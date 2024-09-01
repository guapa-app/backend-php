<?php

namespace App\Notifications;

use App\Channels\WhatsAppChannel;
use App\Models\Offer;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OfferNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Offer
     */
    private $offer;

    /**
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'database', 'fcm', WhatsAppChannel::class,
        ];
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->offer->product_id,
            'summary' => $this->getSummary(),
            'type' => 'new-offer',
            'title' => 'New offer',
            'image' => $this->getImage(),
        ];
    }

    /**
     * Get fcm representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return FcmMessage
     */
    public function toFcm($notifiable): FcmMessage
    {
        $message = new FcmMessage();
        $message->content([
            'title' => 'خصم ' . $this->offer->discount_string . ' على ' . $this->offer->product->title,
            'body' => $this->getSummary(),
            'sound' => 'default', // Optional
            'icon' => '', // Optional
            'click_action' => '', // Optional
        ])->data([
            'type' => 'new-offer',
            'product_id' => $this->offer->product->id,
        ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.

        return $message;
    }

    public function toWhatsapp($notifiable)
    {
        return [
            'client' => $notifiable->phone,
            'campaignName' => 'offersprovidersservices',
            'variables' => [
                'username' => $notifiable->name,
                'discount' => $this->offer->discount_string,
                'image' => $this->getImage(),
                'title' => $this->offer->product->title,
            ],
            'campaignVersion' => '01916c78-2738-877c-032a-6200d8561815',

        ];
    }

    public function getSummary()
    {
        return 'خصم ' . $this->offer->discount_string . ' على ' . $this->offer->product->title . ' من ' .
            $this->offer->product->vendor->name;
    }

    public function getImage()
    {
        return $this->offer->image?->url ?? $this->offer->product->image?->url;
    }
}
