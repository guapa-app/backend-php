<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ErrorAlarmMail extends Mailable
{
    use Queueable, SerializesModels;

    private $errorMessage;
    private $exception;

    /**
     * Create a new message instance.
     * @param $errorMessage
     */
    public function __construct(string $errorMessage = 'Error', $exception = [])
    {
        $this->errorMessage = $errorMessage;
        $this->exception = $exception;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('postmaster@mg.cosmo.promo', 'Guapa'),
            subject: 'Guapa Error Alarm Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.errors',
            with: [
                'message' => $this->errorMessage,
                'exception' => $this->exception,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
