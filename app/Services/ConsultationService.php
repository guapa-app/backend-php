<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\WalletService;
use App\Services\PaymentService;

class ConsultationService
{
    protected $walletService;
    protected $paymentService;

    public function __construct(WalletService $walletService, PaymentService $paymentService)
    {
        $this->walletService = $walletService;
        $this->paymentService = $paymentService;
    }

    /**
     * Create a new consultation
     *
     * @param array $data
     * @param \App\Models\User $user
     * @return \App\Models\Consultation
     */
    public function createConsultation(array $data, $user)
    {
        DB::beginTransaction();

        try {
            // Add user ID to data
            $data['user_id'] = $user->id;

            // Check if the time slot is available
            if (!$this->isTimeSlotAvailable($data['vendor_id'], $data['appointment_date'], $data['appointment_time'])) {
                throw new \Exception('The selected time slot is not available');
            }

            // Calculate consultation fees
            $consultationFees = $this->calculateConsultationFees($data['vendor_id']);
            $data = array_merge($data, $consultationFees);

            // Process payment
            $paymentData = $this->processPayment($user, $data['payment_method'], $data['payment_reference'] ?? null, $data['total_amount']);
            $data = array_merge($data, $paymentData);
            // Create consultation
            $consultation = Consultation::create($data);

            $this->updateMedia($consultation, $data);

            // Send notifications
            $this->sendNotifications($consultation);

            DB::commit();
            return $consultation;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get available time slots for a vendor on a specific date
     *
     * @param int $vendorId
     * @param string $date
     * @return array
     */
    public function getAvailableTimeSlots($vendorId, $date)
    {
        $vendor = Vendor::findOrFail($vendorId);
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;

        // Get regular working hours for this day
        $schedule = $vendor->workDays()
            ->where(function($query) use ($dayOfWeek) {
                $query->where('day', $dayOfWeek)
                    ->orWhere('day', 7); // 7 is for all days
            })
            ->first();

        // If no working hours set for this day
        if (!$schedule) {
            return [];
        }

        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);
        $sessionDuration = $vendor->session_duration;

        // Generate time slots
        $slots = [];
        $currentSlot = Carbon::parse($date . ' ' . $startTime->format('H:i'));
        $endDateTime = Carbon::parse($date . ' ' . $endTime->format('H:i'));

        while ($currentSlot->addMinutes($sessionDuration) <= $endDateTime) {
            $slotStart = clone $currentSlot;
            $slotStart->subMinutes($sessionDuration); // Go back to start of this slot

            if ($this->isTimeSlotAvailable($vendorId, $date, $slotStart->format('H:i'))) {
                $slots[] = [
                    'start_time' => $slotStart->format('H:i'),
                    'end_time' => $currentSlot->format('H:i'),
                    'duration' => $sessionDuration
                ];
            }
        }

        return $slots;
    }

    /**
     * Check if a time slot is available
     *
     * @param int $vendorId
     * @param string $date
     * @param string $time
     * @return bool
     */
    public function isTimeSlotAvailable($vendorId, $date, $time)
    {
        // Check if the vendor is available at this time
        $vendor = Vendor::findOrFail($vendorId);
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;

        $schedule = $vendor->workDays()
            ->where(function($query) use ($dayOfWeek) {
                $query->where('day', $dayOfWeek)
                    ->orWhere('day', 7); // 7 is for all days
            })
            ->first();

        if (!$schedule) {
            return false;
        }

        $appointmentTime = Carbon::parse($time);
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);

        if ($appointmentTime < $startTime || $appointmentTime >= $endTime) {
            return false;
        }

        // Check if this slot is already booked
        $isBooked = Consultation::where('vendor_id', $vendorId)
            ->where('appointment_date', $date)
            ->where('appointment_time', $time)
            ->where('status', '!=', 'cancelled')
            ->exists();

        return !$isBooked;
    }

    /**
     * Calculate consultation fees
     *
     * @param int $vendorId
     * @return array
     */
    public function calculateConsultationFees($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        $consultationFees = $vendor->consultation_fees;

        return [
            'consultation_fee' => $consultationFees['consultation_fees'],
            'application_fees' => $consultationFees['application_fee'],
            'tax_amount' => $consultationFees['tax_amount'],
            'total_amount' => $consultationFees['total_amount']
        ];
    }

    /**
     * Process payment
     *
     * @param \App\Models\User $user
     * @param string $paymentMethod
     * @param string|null $paymentReference
     * @param float $amount
     * @return array
     */
    protected function processPayment($user, $paymentMethod, $paymentReference, $amount)
    {
        $paymentData = [];

        if ($paymentMethod === 'wallet') {
            $walletAmount = $user->myWallet()->balance;

            if ($walletAmount < $amount) {
                throw new \Exception('Insufficient wallet balance');
            }

            // Deduct the amount from the wallet
            $transaction = $this->walletService->debit($user, $amount);

            $paymentData['payment_reference'] = $transaction->transaction_number;
            $paymentData['payment_status'] = 'paid';
        } elseif ($paymentMethod === 'credit_card') {
            if (!$this->paymentService->isPaymentPaidSuccessfully($paymentReference)) {
                throw new \Exception('Payment details are incorrect. Please check your payment again.');
            }

            $paymentData['payment_reference'] = $paymentReference;
            $paymentData['payment_status'] = 'paid';
        } else {
            throw new \Exception('Invalid payment method');
        }

        return $paymentData;
    }

    /**
     * Send notifications to user and vendor
     *
     * @param \App\Models\Consultation $consultation
     */
    protected function sendNotifications($consultation)
    {
        // Implement notification logic here
        // This could include sending emails, push notifications, etc.
    }

    public function updateMedia(Consultation $consultation, array $data): Consultation
    {
        (new MediaService())->handleMedia($consultation, $data);

        $consultation->load('media');

        return $consultation;
    }

    public function reject($consultation): Consultation
    {
        $vendor = Auth::user()->vendor;
        if (!$vendor || $vendor->id !== $consultation->vendor_id) {
            throw new \Exception(__('Unauthorized'), 403);
        }

        if (!$consultation) {
            throw new \Exception(__('Consultation not found'), 404);
        }

        // Check if the consultation can be rejected
        if (!$consultation->canReject()) {
            throw new \Exception(__('Cannot reject this consultation. It must be at least 6 hours before the appointment time.'), 400);
        }

        // Check if the consultation is already cancelled or rejected
        if ($consultation->status === Consultation::STATUS_CANCELLED) {
            throw new \Exception(__('Consultation is already cancelled'), 400);
        }

        if ($consultation->status === Consultation::STATUS_REJECTED) {
            throw new \Exception(__('Consultation is already rejected'), 400);
        }

        $consultation->status = Consultation::STATUS_REJECTED;
        $consultation->rejected_at = now();
        $consultation->save();

        // TODO: Send notification to the user about the rejection

        return $consultation;
    }
}
