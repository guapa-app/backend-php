<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class ConsultationInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $consultation;
    protected $isForVendor;

    /**
     * Create a new notification instance.
     *
     * @param Consultation $consultation
     * @param bool $isForVendor Whether this is for the vendor or user
     * @return void
     */
    public function __construct(Consultation $consultation, bool $isForVendor = false)
    {
        $this->consultation = $consultation;
        $this->isForVendor = $isForVendor;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $appointmentDateTime = Carbon::parse(
            $this->consultation->appointment_date->format('Y-m-d') .
            ' ' .
            $this->consultation->appointment_time->format('H:i:s')
        );

        $user = $this->consultation->user;
        $vendor = $this->consultation->vendor;

        $recipient = $this->isForVendor ? $vendor->name : $user->name;
        $otherParty = $this->isForVendor ? $user->name : $vendor->name;

        $icsContent = $this->generateIcsContent();

        $mail = (new MailMessage)
            ->subject('Online Consultation Meeting Invitation')
            ->greeting('Hello ' . $recipient . ',')
            ->line('An online consultation has been scheduled.')
            ->line('Consultation with: ' . $otherParty)
            ->line('Date: ' . $appointmentDateTime->format('l, F j, Y'))
            ->line('Time: ' . $appointmentDateTime->format('g:i A') .
                  ' (' . config('app.timezone') . ')')
            ->line('Duration: ' . ($vendor->session_duration ?? 60) . ' minutes')
            ->line('Reason: ' . $this->consultation->consultation_reason);

        // Only include the join link if meeting data is available
        if ($this->consultation->session_url) {
            $mail->action('Join Meeting', $this->consultation->session_url);

            if ($this->consultation->session_password) {
                $mail->line('Meeting Password: ' . $this->consultation->session_password);
            }
        }

        $mail->line('Please be available at the scheduled time.')
             ->line('Thank you for using our platform!');

        // Add the ICS calendar attachment
        if ($icsContent) {
            $mail->attachData(
                $icsContent,
                'consultation_' . $this->consultation->id . '.ics',
                ['mime' => 'text/calendar']
            );
        }

        return $mail;
    }

    /**
     * Generate ICS calendar file content
     *
     * @return string|null
     */
    protected function generateIcsContent()
    {
        try {
            $appointmentDateTime = Carbon::parse(
                $this->consultation->appointment_date->format('Y-m-d') .
                ' ' .
                $this->consultation->appointment_time->format('H:i:s')
            );

            $vendor = $this->consultation->vendor;
            $user = $this->consultation->user;
            $duration = $vendor->session_duration ?? 60;

            $endTime = $appointmentDateTime->copy()->addMinutes($duration);

            $icsContent = "BEGIN:VCALENDAR\r\n";
            $icsContent .= "VERSION:2.0\r\n";
            $icsContent .= "PRODID:-//Guapa//Online Consultation//EN\r\n";
            $icsContent .= "CALSCALE:GREGORIAN\r\n";
            $icsContent .= "METHOD:REQUEST\r\n";
            $icsContent .= "BEGIN:VEVENT\r\n";
            $icsContent .= "UID:" . uniqid() . "@" . config('app.url') . "\r\n";
            $icsContent .= "DTSTAMP:" . now()->format('Ymd\THis\Z') . "\r\n";
            $icsContent .= "DTSTART:" . $appointmentDateTime->format('Ymd\THis\Z') . "\r\n";
            $icsContent .= "DTEND:" . $endTime->format('Ymd\THis\Z') . "\r\n";
            $icsContent .= "SUMMARY:Online Consultation with " .
                          ($this->isForVendor ? $user->name : $vendor->name) . "\r\n";

            // Add location (Zoom URL)
            if ($this->consultation->session_url) {
                $icsContent .= "LOCATION:" . $this->consultation->session_url . "\r\n";

                // Add a description with meeting details
                $description = "Online Consultation\n";
                $description .= "Join URL: " . $this->consultation->session_url . "\n";

                if ($this->consultation->session_password) {
                    $description .= "Password: " . $this->consultation->session_password . "\n";
                }

                $description .= "Reason: " . $this->consultation->consultation_reason . "\n";

                $icsContent .= "DESCRIPTION:" . str_replace("\n", "\\n", $description) . "\r\n";
            }

            $icsContent .= "STATUS:CONFIRMED\r\n";
            $icsContent .= "SEQUENCE:0\r\n";

            // Add reminder (15 minutes before)
            $icsContent .= "BEGIN:VALARM\r\n";
            $icsContent .= "ACTION:DISPLAY\r\n";
            $icsContent .= "DESCRIPTION:Reminder\r\n";
            $icsContent .= "TRIGGER:-PT15M\r\n";
            $icsContent .= "END:VALARM\r\n";

            $icsContent .= "END:VEVENT\r\n";
            $icsContent .= "END:VCALENDAR\r\n";

            return $icsContent;
        } catch (\Exception $e) {
            \Log::error('Failed to generate ICS file: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $appointmentDateTime = Carbon::parse(
            $this->consultation->appointment_date->format('Y-m-d') .
            ' ' .
            $this->consultation->appointment_time->format('H:i:s')
        );

        return [
            'consultation_id' => $this->consultation->id,
            'vendor_id' => $this->consultation->vendor_id,
            'user_id' => $this->consultation->user_id,
            'appointment_datetime' => $appointmentDateTime->format('Y-m-d H:i:s'),
            'meeting_url' => $this->consultation->session_url,
            'type' => 'meeting_invitation',
            'message' => 'You have a scheduled online consultation on ' .
                         $appointmentDateTime->format('F j, Y \a\t g:i A')
        ];
    }
}
