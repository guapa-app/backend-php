<?php

namespace App\Notifications;

use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PushNotification extends Notification
{
    use Queueable;

    private $title;
    private $summary;
    private $image;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $summary, $image)
    {
        $this->title = $title;
        $this->summary = $summary;
        $this->image = $image;
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
            'title' => $this->title,
            'summary' => $this->summary,
            'image' => $this->image,
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
            'title' => $this->title,
            'body' => $this->summary,
            'image' => $this->image,
            'sound' => 'default',
            'icon' => '',
            'click_action' => '',
        ])->data([
            'title' => $this->title,
            'summary' => $this->summary,
            'image' => $this->image,
        ])->priority(FcmMessage::PRIORITY_HIGH);

        return $message;
    }
}
