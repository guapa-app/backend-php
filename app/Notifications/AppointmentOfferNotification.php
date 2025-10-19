<?php

namespace App\Notifications;

use App\Channels\FirebaseChannel;
use App\Models\AppointmentOffer;
use App\Models\User;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AppointmentOfferNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The user who made the order.
     *
     * @var User
     */
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(public AppointmentOffer $appointmentOffer)
    {
        $this->user = $appointmentOffer->user;
        $this->afterCommit();
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

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->appointmentOffer->id,
            'summary' => $this->getSummary(),
            'type' => 'new-appointment',
            'title' => 'New Appointment Offer',
            'image' => '',
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
            'title' => 'New appointment',
            'body' => 'New appointment from '.$this->user->name,
            'sound' => 'default',
            'icon' => '',
            'click_action' => '',
        ])->data([
            'type' => 'new-appointment',
            'summary' => $this->getSummary(),
            'id' => $this->appointmentOffer->id,
        ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.

        return $message;
    }

    public function toFirebase()
    {
        return [
            'title' => 'New appointment',
            'body' => $this->getSummary(),
        ];
    }
    public function getSummary(): string
    {
        return 'لديك طلب استشارة جديد';
    }
}
