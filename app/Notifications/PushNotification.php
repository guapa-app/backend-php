<?php

namespace App\Notifications;

use App\Channels\FirebaseChannel;
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
        return [
            FirebaseChannel::class,
            'database',
        ];
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title'     => $this->title,
            'summary'   => $this->summary,
            'image'     => $this->image,
            'type'      => 'push-notifications',
        ];
    }

    /**
     * Get fcm representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toFirebase()
    {
        return [
            'title' => $this->title,
            'body' => $this->summary,
        ];
    }
}
