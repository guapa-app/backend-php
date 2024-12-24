<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Enums\TransactionStatus;

class PayoutStatusNotification extends Notification
{
    use Queueable;

    public function __construct(private Transaction $transaction)
    {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $amount = abs($this->transaction->amount);
        $message = new MailMessage;

        switch ($this->transaction->status) {
            case TransactionStatus::COMPLETED:
                $message
                    ->subject('Payout Successfully Processed')
                    ->line("Your payout of {$amount} has been successfully processed.")
                    ->line("Transaction Reference: {$this->transaction->transaction_number}")
                    ->line('The amount should be reflected in your bank account within 1-3 business days.')
                    ->action('View Transaction', url("/vendor/transactions/{$this->transaction->id}"));
                break;

            case TransactionStatus::FAILED:
                $message
                    ->error()
                    ->subject('Vendor Payout Failed')
                    ->line("Payout failed for vendor #{$this->transaction->vendor->name}")
                    ->line("Amount: {$amount}")
                    ->line("Error: {$this->transaction->notes}");
                break;
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $amount = abs($this->transaction->amount);

        return [
            'transaction_id' => $this->transaction->id,
            'amount' => $amount,
            'status' => $this->transaction->status,
            'transaction_number' => $this->transaction->transaction_number,
            'notes' => $this->transaction->notes,
            'date' => $this->transaction->transaction_date
        ];
    }
}
