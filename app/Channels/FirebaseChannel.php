<?php

namespace App\Channels;

use App\Services\FirebaseService;
use Illuminate\Notifications\Notification;

class FirebaseChannel
{
    protected $firebaseService;

    /**
     * FirebaseChannel constructor.
     *
     * @param FirebaseService $firebaseService
     */
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // Get the device tokens and message content from the notification
        $title = $notification->toFirebase()['title'];
        $body = $notification->toFirebase()['body'];
        $data = $notification->toFirebase()['data'] ?? [];

        // Send the notification via FirebaseService
        $this->firebaseService->sendNotification(notifiable: $notifiable, title: $title, body: $body, data: $data);
    }
}
