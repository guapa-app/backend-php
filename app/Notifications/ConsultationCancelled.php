<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    protected $consultation;

    /**
     * Create a new notification instance.
     *
     * @param Consultation $consultation
     * @return void
     */
    public function __construct(Consultation $consultation)
    {
        $this->consultation = $consultation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $vendor = $this->consultation->vendor;
        $dateTime = $this->consultation->appointment_date->format('l, F j, Y') . ' at ' .
            $this->consultation->appointment_time->format('g:i A');

        $reason = "";
        if ($this->consultation->status === 'cancelled') {
            $reason = "This consultation has been cancelled.";
        } else if ($this->consultation->status === 'rejected') {
            $reason = "This consultation has been rejected by the healthcare provider.";
        }

        return (new MailMessage)
            ->subject('Consultation Cancelled')
            ->line("Your consultation with {$vendor->name} scheduled for {$dateTime} has been cancelled.")
            ->line($reason)
            ->line("If you have any questions, please contact our support team.");
    }
}
