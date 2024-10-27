<?php

namespace App\Notifications;

use App\Channels\FirebaseChannel;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Lang;

/**
 * Chat message notification.
 */
class ChatMessage extends Notification
{
    use Queueable;

    private $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = [
            FirebaseChannel::class,
            'broadcast'
        ];

        if ($this->message->type === 'offer') {
            $channels[] = 'database';
        }

        return $channels;
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
            'title' => $this->getSenderName(),
            'body' => $this->getMessageBody(),
            'sound' => 'default', // Optional
            'icon' => '', // Optional
            'click_action' => '', // Optional
        ])->data([
            'type' => 'message',
            'is_offer' => $this->message->type === 'offer',
            'message' => $this->message,
            'resource' => 'conversations',
            'id' => $this->message->conversation_id,
        ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.

        return $message;
    }

    public function toFirebase()
    {
        return [
            'title' => $this->getSenderName(),
            'body' => $this->getMessageBody()
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'message',
            'is_offer' => $this->message->type === 'offer',
            'message' => $this->message,
            'resource' => 'conversations',
            'id' => $this->message->conversation_id,
            'summary' => $this->getMessageBody(),
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'message' => $this->message,
        ]))->onQueue('chat-broadcasts');
    }

    /**
     * Get the type of the notification being broadcast.
     *
     * @return string
     */
    public function broadcastType()
    {
        return 'new-message';
    }

    public function getMessageBody()
    {
        if ($this->message->type === 'offer') {
            return Lang::get('New offer from') . ' ' . $this->getSenderName();
        } else {
            return $this->message->message;
        }
    }

    public function getSenderName()
    {
        return $this->message->participant->messageable->name;
    }
}
