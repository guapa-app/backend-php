<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Media;
use App\Models\GiftCard;
use App\Models\GiftCardSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GiftCardService
{
    protected $walletService;
    protected $paymentService;
    protected $taxService;
    protected $transactionService;

    public function __construct(
        WalletService $walletService,
        PaymentService $paymentService,
        TaxService $taxService,
        TransactionService $transactionService
    ) {
        $this->walletService = $walletService;
        $this->paymentService = $paymentService;
        $this->taxService = $taxService;
        $this->transactionService = $transactionService;
    }

    /**
     * Create a new gift card
     *
     * @param array $data
     * @param \App\Models\User $user
     * @return \App\Models\GiftCard
     */
    public function createGiftCard(array $data, $user)
    {
        DB::beginTransaction();

        try {
            // Add sender ID to data
            $data['sender_id'] = $user->id;

            // Calculate taxes and total amount
            $taxAmount = $this->calculateTaxAmount($data['amount']);
            $data['tax_amount'] = $taxAmount;
            $data['total_amount'] = $data['amount'] + $taxAmount;

            // Set initial status as pending
            $data['status'] = GiftCard::STATUS_PENDING;
            $data['payment_status'] = GiftCard::PAYMENT_STATUS_PENDING;

            // Set default expiry date if not provided
            if (empty($data['expires_at'])) {
                $defaultExpirationDays = GiftCardSetting::getDefaultExpirationDays();
                $data['expires_at'] = now()->addDays($defaultExpirationDays);
            }

            // Validate amount against settings
            $minAmount = GiftCardSetting::getMinAmount();
            $maxAmount = GiftCardSetting::getMaxAmount();

            if ($data['amount'] < $minAmount || $data['amount'] > $maxAmount) {
                throw new \Exception("Amount must be between {$minAmount} and {$maxAmount}.");
            }

            // Create gift card
            $giftCard = GiftCard::create($data);

            // Attach media if provided
            $this->updateMedia($giftCard, $data);

            // Generate QR code synchronously as part of the creation process
            $giftCard->generateQrCode();

            // Load relationships including QR code
            $giftCard->load(['media', 'qrCode']);
            DB::commit();
            return $giftCard;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Change payment status for credit card payments
     *
     * @param array $data Contains gift_card_id and payment_id
     * @return \App\Models\GiftCard
     */
    public function changePaymentStatus(array $data)
    {
        DB::beginTransaction();

        try {
            $giftCard = GiftCard::findOrFail($data['id']);

            if ($giftCard->payment_status === GiftCard::PAYMENT_STATUS_PAID) {
                throw new \Exception('Payment already processed for this gift card');
            }

            // Update payment data
            $giftCard->payment_status = GiftCard::PAYMENT_STATUS_PAID;
            $giftCard->payment_reference = $data['payment_id'];
            $giftCard->payment_method = 'credit_card';
            $giftCard->payment_gateway = $data['payment_gateway'] ?? 'moyasar';

            // Update status to active
            $giftCard->status = GiftCard::STATUS_ACTIVE;
            $giftCard->save();

            // Generate invoice URL if needed
            if (empty($giftCard->invoice_url)) {
                $giftCard->invoice_url = $this->generateInvoiceUrl($giftCard);
                $giftCard->save();
            }

            // Send notifications
            $this->sendNotifications($giftCard);

            // Load relationships including QR code
            $giftCard->load(['media', 'qrCode']);
            DB::commit();
            return $giftCard;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process wallet payment for gift cards
     *
     * @param \App\Models\User $user
     * @param array $data Contains gift_card_id
     * @return \App\Models\GiftCard
     */
    public function payViaWallet($user, array $data)
    {
        DB::beginTransaction();

        try {
            $giftCard = GiftCard::findOrFail($data['id']);

            if ($giftCard->payment_status === GiftCard::PAYMENT_STATUS_PAID) {
                throw new \Exception('Payment already processed for this gift card');
            }

            // Check wallet balance
            $walletAmount = $user->myWallet()->balance;
            if ($walletAmount < $giftCard->total_amount) {
                throw new \Exception('Insufficient wallet balance');
            }

            // Deduct from wallet
            $transaction = $this->walletService->debit($user, $giftCard->total_amount);

            // Update payment data
            $giftCard->payment_status = GiftCard::PAYMENT_STATUS_PAID;
            $giftCard->payment_reference = $transaction->transaction_number;
            $giftCard->payment_method = 'wallet';
            $giftCard->payment_gateway = 'wallet';

            // Update status to active
            $giftCard->status = GiftCard::STATUS_ACTIVE;
            $giftCard->save();

            // Generate invoice URL if needed
            if (empty($giftCard->invoice_url)) {
                $giftCard->invoice_url = $this->generateInvoiceUrl($giftCard);
                $giftCard->save();
            }

            // Send notifications
            $this->sendNotifications($giftCard);

            // Load relationships including QR code
            $giftCard->load(['media', 'qrCode']);
            DB::commit();
            return $giftCard;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Calculate tax amount for gift card
     *
     * @param float $amount
     * @return float
     */
    public function calculateTaxAmount($amount)
    {
        // You can implement your tax calculation logic here
        // For now, using a simple 15% tax rate (VAT in Saudi Arabia)
        $taxRate = 0.15;
        return $amount * $taxRate;
    }

    /**
     * Generate invoice URL for gift card
     *
     * @param \App\Models\GiftCard $giftCard
     * @return string
     */
    protected function generateInvoiceUrl($giftCard)
    {
        // You can implement invoice generation logic here
        // For now, returning a placeholder
        return config('app.url') . '/gift-cards/' . $giftCard->id . '/invoice';
    }

    /**
     * Send notifications to user and recipient
     *
     * @param \App\Models\GiftCard $giftCard
     */
    protected function sendNotifications($giftCard)
    {
        // Notify the sender
        if ($giftCard->sender) {
            // $giftCard->sender->notify(new \App\Notifications\GiftCardCreatedNotification($giftCard));
        }

        // Notify the recipient if they have an account
        if ($giftCard->recipient) {
            // $giftCard->recipient->notify(new \App\Notifications\GiftCardReceivedNotification($giftCard));
        }

        // Send email to recipient if email is provided
        if ($giftCard->recipient_email) {
            // Mail::to($giftCard->recipient_email)->send(new \App\Mail\GiftCardReceivedMail($giftCard));
        }
    }

    /**
     * Update gift card media
     *
     * @param \App\Models\GiftCard $giftCard
     * @param array $data
     * @return \App\Models\GiftCard
     */
    public function updateMedia(GiftCard $giftCard, array $data): GiftCard
    {
        $keep_media = $data['keep_media'] ?? [];
        $giftCard->media()->whereNotIn('id', $keep_media)->delete();

        if (!empty($data['media_ids'])) {
            Media::whereIn('id', $data['media_ids'])
                ->update([
                    'model_type' => 'App\Models\GiftCard',
                    'model_id' => $giftCard->id,
                    'collection_name' => 'gift_card_backgrounds'
                ]);
        }

        $giftCard->load('media');
        return $giftCard;
    }

    /**
     * Cancel a gift card
     *
     * @param \App\Models\GiftCard $giftCard
     * @param \App\Models\User $user
     * @return \App\Models\GiftCard
     */
    public function cancelGiftCard(GiftCard $giftCard, $user)
    {
        if ($user->id !== $giftCard->sender_id) {
            throw new \Exception(__('Unauthorized'), 403);
        }

        if (!$giftCard) {
            throw new \Exception(__('Gift card not found'), 404);
        }

        // Check if the gift card is already cancelled
        if ($giftCard->status === GiftCard::STATUS_CANCELLED) {
            throw new \Exception(__('Gift card is already cancelled'), 400);
        }

        // Add logic here for refunds if needed based on your cancellation policy
        if ($giftCard->payment_status === GiftCard::PAYMENT_STATUS_PAID) {
            // Implement refund logic here
            // $this->processRefund($giftCard);
        }

        $giftCard->status = GiftCard::STATUS_CANCELLED;
        $giftCard->save();

        return $giftCard;
    }

    /**
     * Process refund for gift card
     *
     * @param \App\Models\GiftCard $giftCard
     * @return bool
     */
    protected function processRefund($giftCard)
    {
        // Implement refund logic based on payment method
        if ($giftCard->payment_method === 'wallet') {
            // For wallet payments, we can create a new transaction to credit the user
            $user = $giftCard->sender;
            $transactionType = \App\Enums\TransactionType::RECHARGE;
            $transactionOperation = \App\Enums\TransactionOperation::DEPOSIT;

            $this->transactionService->createTransaction(
                $user->id,
                $giftCard->total_amount,
                $transactionType,
                $transactionOperation
            );

            // Update wallet balance
            $wallet = $user->myWallet();
            $wallet->balance += $giftCard->total_amount;
            $wallet->save();
        } else {
            // Process external payment gateway refund
            // $this->paymentService->refund($giftCard->payment_reference);
        }

        $giftCard->payment_status = GiftCard::PAYMENT_STATUS_REFUNDED;
        $giftCard->save();

        return true;
    }

    /**
     * Get gift card statistics
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function getStatistics($user)
    {
        $sentGiftCards = GiftCard::where('sender_id', $user->id);
        $receivedGiftCards = GiftCard::where(function ($query) use ($user) {
            $query->where('recipient_id', $user->id)
                ->orWhere('user_id', $user->id)
                ->orWhere('recipient_email', $user->email)
                ->orWhere('recipient_number', $user->phone);
        });

        return [
            'sent' => [
                'total' => $sentGiftCards->count(),
                'active' => $sentGiftCards->where('status', GiftCard::STATUS_ACTIVE)->count(),
                'used' => $sentGiftCards->where('status', GiftCard::STATUS_USED)->count(),
                'total_amount' => $sentGiftCards->sum('amount'),
            ],
            'received' => [
                'total' => $receivedGiftCards->count(),
                'active' => $receivedGiftCards->where('status', GiftCard::STATUS_ACTIVE)->count(),
                'used' => $receivedGiftCards->where('status', GiftCard::STATUS_USED)->count(),
                'total_amount' => $receivedGiftCards->sum('amount'),
            ],
        ];
    }
}
