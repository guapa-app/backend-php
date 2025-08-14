<?php

namespace App\Services;

use App\Models\User;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        // Initialize Firebase SDK with service account JSON
        $firebase = (new Factory)
            ->withServiceAccount(base_path('firebase.json'));

        // Get Firebase Messaging
        $this->messaging = $firebase->createMessaging();
    }

    /**
     * Send a notification to a specific device token.
     *
     * @param string $deviceToken
     * @param string $title
     * @param string $body
     */
    public function sendNotification($notifiable, $title, $body)
    {
        $tokens = $this->getTokens($notifiable);

        if (count($tokens) > 0) {
            // Create a notification
            $notification = Notification::create($title, $body);

            // Create a Cloud Message with the notification
            $message = CloudMessage::new()->withNotification($notification)
                    ->withData([
                        'type' => 'Test type',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ]);

            // Send the message to multiple device tokens
            $result = $this->messaging->sendMulticast($message, $tokens);

            return $result;
        }
    }

    private function getTokens($notifiable)
    {
        if ($notifiable instanceof User) {
            $deviceTokens = $notifiable->devices->pluck('fcmtoken')->toArray();

            foreach ($deviceTokens as $token) {
                if (is_null($token)) {
                    continue;
                }
                $tokens[] = $token;
            }

            return $tokens ?? [];
        } else {
            return [];
        }
    }
}
