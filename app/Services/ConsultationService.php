<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Media;
use App\Models\Vendor;
use App\Models\Consultation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\Meetings\MeetingServiceFactory;

class ConsultationService
{
    protected $walletService;
    protected $paymentService;
    protected $meetingProvider;

    public function __construct(WalletService $walletService, PaymentService $paymentService)
    {
        $this->walletService = $walletService;
        $this->paymentService = $paymentService;
        $this->meetingProvider = config('services.meetings.default_provider', 'zoom');
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

            // Set initial status as pending
            $data['status'] = Consultation::STATUS_PENDING;
            $data['payment_status'] = 'pending';

            // Create consultation
            $consultation = Consultation::create($data);

            // Attach media if provided
            if (isset($data['media'])) {
                $this->updateMedia($consultation, $data);
            }

            DB::commit();
            return $consultation;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Change payment status for credit card payments
     *
     * @param array $data Contains consultation_id and payment_id
     * @return \App\Models\Consultation
     */
    public function changePaymentStatus(array $data)
    {
        DB::beginTransaction();

        try {
            $consultation = Consultation::findOrFail($data['id']);

            if ($consultation->payment_status === 'paid') {
                throw new \Exception('Payment already processed for this consultation');
            }

            // Update payment data
            $consultation->payment_status = 'paid';
            $consultation->payment_reference = $data['payment_id'];
            $consultation->payment_method = 'credit_card';

            // update status to scheduled
            $consultation->status = Consultation::STATUS_SCHEDULED;
            $consultation->save();

            // Create the virtual meeting
            $this->completeConsultationProcess($consultation);

            DB::commit();
            return $consultation;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process wallet payment for consultations
     *
     * @param \App\Models\User $user
     * @param array $data Contains consultation_id
     * @return \App\Models\Consultation
     */
    public function payViaWallet($user, array $data)
    {
        DB::beginTransaction();

        try {
            $consultation = Consultation::findOrFail($data['id']);

            if ($consultation->payment_status === 'paid') {
                throw new \Exception('Payment already processed for this consultation');
            }

            // Check wallet balance
            $walletAmount = $user->myWallet()->balance;
            if ($walletAmount < $consultation->total_amount) {
                throw new \Exception('Insufficient wallet balance');
            }

            // Deduct from wallet
            $transaction = $this->walletService->debit($user, $consultation->total_amount);

            // Update payment data
            $consultation->payment_status = 'paid';
            $consultation->payment_reference = $transaction->transaction_number;
            $consultation->payment_method = 'wallet';

            // update status to scheduled
            $consultation->status = Consultation::STATUS_SCHEDULED;
            $consultation->save();

            // Create the virtual meeting
            $this->completeConsultationProcess($consultation);

            DB::commit();
            return $consultation;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Complete consultation process after payment
     *
     * @param \App\Models\Consultation $consultation
     * @return \App\Models\Consultation
     */
    private function completeConsultationProcess(Consultation $consultation)
    {
        // Create the virtual meeting if this is an online consultation
        $vendor = Vendor::findOrFail($consultation->vendor_id);
        $meetingData = $this->createVirtualMeeting($consultation, $vendor);
        $consultation->update($meetingData);

        // Update vendor's wallet
        $this->walletService->creditVendorWallet($vendor->id, $consultation->consultation_fee, $consultation);

        // Send notifications
        $this->sendNotifications($consultation);

        return $consultation;
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
            ->where('type', 'online')
            ->where('is_active', true)
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
     * Send notifications to user and vendor
     *
     * @param \App\Models\Consultation $consultation
     */
    protected function sendNotifications($consultation)
    {
        // Notify the user
        $consultation->user->notify(new \App\Notifications\ConsultationInvitationNotification($consultation, false));

        // Notify the vendor
        $consultation->vendor->notify(new \App\Notifications\ConsultationInvitationNotification($consultation, true));

    }

    public function updateMedia(Consultation $consultation, array $data): Consultation
    {
        $keep_media = $data['keep_media'] ?? [];
        $consultation->media()->whereNotIn('id', $keep_media)->delete();

        $mediaCollections = [
            'media_ids' => 'consultations',
            'before_media_ids' => 'before',
            'after_media_ids' => 'after'
        ];

        foreach ($mediaCollections as $mediaKey => $collectionName) {
            if (!empty($data[$mediaKey])) {
                Media::whereIn('id', $data[$mediaKey])
                    ->update([
                        'model_type' => 'consultation',
                        'model_id' => $consultation->id,
                        'collection_name' => $collectionName
                    ]);
            }
        }

        $consultation->load('media');

        return $consultation;
    }

    /**
     * Update the reject method to also cancel the video conference
     */
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

        // Cancel the video conference
        $this->cancelVideoConference($consultation);

        // Send cancellation notification to the user
        $consultation->user->notify(new \App\Notifications\ConsultationCancelled($consultation));

        return $consultation;
    }

    /**
     * Cancel a consultation
     *
     * @param \App\Models\Consultation $consultation
     * @param \App\Models\User $user
     * @return \App\Models\Consultation
     */
    public function cancel(Consultation $consultation, $user): Consultation
    {
        if ($user->id !== $consultation->user_id) {
            throw new \Exception(__('Unauthorized'), 403);
        }

        if (!$consultation) {
            throw new \Exception(__('Consultation not found'), 404);
        }

        // Check if the consultation is already cancelled or rejected
        if ($consultation->status === Consultation::STATUS_CANCELLED) {
            throw new \Exception(__('Consultation is already cancelled'), 400);
        }

        if ($consultation->status === Consultation::STATUS_REJECTED) {
            throw new \Exception(__('Consultation is already rejected'), 400);
        }

        // Add logic here for refunds if needed based on your cancellation policy

        $consultation->status = Consultation::STATUS_CANCELLED;
        $consultation->cancelled_at = now();
        $consultation->save();

        // Cancel the video conference
        $this->cancelVideoConference($consultation);

        // Send cancellation notification to the vendor
        $consultation->vendor->user->notify(new \App\Notifications\ConsultationCancelled($consultation));

        return $consultation;
    }

    /**
     * Create a virtual meeting for the consultation
     *
     * @param Consultation $consultation
     * @param Vendor $vendor
     * @return array Meeting data to save to the consultation
     */
    protected function createVirtualMeeting(Consultation $consultation, Vendor $vendor)
    {
        try {
            // Get meeting duration from vendor settings or use default
            $duration = $vendor->session_duration ?? 60;

            // Create meeting
            $meetingService = MeetingServiceFactory::create($this->meetingProvider);

            $meetingData = $meetingService->createMeeting([
                'topic' => 'Consultation with ' . $vendor->name,
                'agenda' => 'Online medical consultation',
                'date' => $consultation->appointment_date->format('Y-m-d'),
                'time' => $consultation->appointment_time->format('H:i:s'),
                'duration' => $duration
            ]);

            return [
                'meeting_provider' => $meetingData['provider'],
                'session_url' => $meetingData['join_url'],
                'session_password' => $meetingData['password'] ?? null,
                'meeting_data' => json_encode($meetingData['data']),
            ];
        } catch (\Exception $e) {
            // Log the error but don't fail the consultation creation
            \Log::error('Failed to create meeting for consultation: ' . $e->getMessage());

            return [
                'meeting_provider' => null,
                'session_url' => null,
                'session_password' => null,
                'meeting_data' => null,
            ];
        }
    }

    /**
     * Cancel a consultation's video conference
     * This would be called when a consultation is cancelled or rejected
     *
     * @param \App\Models\Consultation $consultation
     * @return void
     */
    public function cancelVideoConference(Consultation $consultation)
    {
        // Skip if no session metadata
        if (empty($consultation->session_metadata)) {
            return;
        }

        $meetingId = $consultation->session_metadata['meeting_id'];

        $meetingService = MeetingServiceFactory::create($this->meetingProvider);
        $success = $meetingService->deleteMeeting($meetingId);

        if ($success) {
            // Clear session URLs but keep metadata for reference
            $consultation->session_url = null;
            $consultation->session_metadata = array_merge(
                $consultation->session_metadata,
                ['cancelled_at' => now()->toDateTimeString()]
            );
            $consultation->save();
        } else {
            \Log::error('Failed to cancel video conference for consultation: ' . $consultation->id);
        }
    }
}
