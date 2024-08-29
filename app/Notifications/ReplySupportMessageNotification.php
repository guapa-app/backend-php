<?php

namespace App\Notifications;

use App\Enums\SupportMessageSenderType;
use App\Models\SupportMessage;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReplySupportMessageNotification extends Notification
{
    use Queueable;

    private $supportMessage;

    public function __construct(SupportMessage $supportMessage)
    {
        $this->supportMessage = $supportMessage;
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
            'title'      => $this->getTitle(),
            'summary'    => $this->getSummary(),
            'type'       => $this->getType(),
            'image'      => '',
            'support_message_id' => $this->supportMessage->parent_id,
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
            'title' => $this->getTitle(),
            'body' => $this->getSummary(),
            'sound' => 'default',
            'icon' => '',
            'click_action' => '',
        ])->data([
            'title' => $this->getTitle(),
            'summary' => $this->getSummary(),
        ])->priority(FcmMessage::PRIORITY_HIGH);

        return $message;
    }

    public function getTitle(): string
    {
        return 'Support Message';
    }

    public function getSummary(): string
    {
        return 'You have a new reply to your support ticket.';
    }

    private function getType(): string
    {
        return 'user-ticket';
    }
}
