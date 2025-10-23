<?php

namespace App\Services\V3_1;

use App\Contracts\Repositories\V3_1\BkamConsultationRepositoryInterface;
use App\Enums\BkamConsultaionStatus;
use App\Models\BkamConsultation;
use App\Models\Media;
use App\Models\Setting;
use App\Services\PaymentService;
use App\Services\QrCodeService;
use App\Services\WalletService;
use DB;

class BkamConsultationService
{
    protected $paymentService;
    protected $meetingProvider;
    protected $bkamConsultationRepository;
    public function __construct(WalletService $walletService, PaymentService $paymentService, BkamConsultationRepositoryInterface $bkamConsultationRepository)
    {
        $this->walletService = $walletService;
        $this->paymentService = $paymentService;
        $this->bkamConsultationRepository = $bkamConsultationRepository;
    }

    /**
     * Create a new bkam consultation
     *
     * @param array $data
     * @param \App\Models\User $user
     * @return \App\Models\BkamConsultation
     */
    public function createConsultation(array $data, $user)
    {
        try {
            DB::beginTransaction();
            // Add user ID to data
            $data['user_id'] = $user->id;
            $fees = Setting::getBkamConsultationFee();
            $taxes = 0; // no Taxes for Bkam Consultation

            // Set initial status as pending
            $data['status'] = BkamConsultaionStatus::Pending;
            $data['consultation_fee'] = $fees;
            $data['taxes'] = 0;
            $data['payment_status'] = 'pending';

            // Create consultation
            $consultation = $this->bkamConsultationRepository->create($data);

            // Generate invoice for the order
            $invoice = $this->paymentService->generateInvoice($consultation, "Bkam Consultation Invoice", 50, 0);
            $consultation->invoice_url = $invoice->url;
            $consultation->save();

            // Attach media if provided
            $this->updateMedia($consultation, $data);

            $consultation->load(relations: 'media');
            DB::commit();
            return $consultation;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function changePaymentStatus(array $data)
    {
        try {
            DB::beginTransaction();
            $consultation = BkamConsultation::findOrFail($data['id']);

            if ($consultation->payment_status === 'paid') {
                throw new \Exception('Payment already processed for this consultation');
            }

            // Update payment data
            $consultation->payment_status = 'paid';
            $consultation->payment_reference = $data['payment_id'];
            $consultation->payment_method = 'credit_card';

            $consultation->save();
            DB::commit();
            return $consultation;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function payViaWallet($user, array $data)
    {
        try {
            DB::beginTransaction();
            $consultation = BkamConsultation::findOrFail($data['id']);

            if ($consultation->payment_status === 'paid') {
                throw new \Exception('Payment already processed for this consultation');
            }

            // Check wallet balance
            $walletAmount = $user->myWallet()->balance;
            if ($walletAmount < $consultation->total_amount) {
                throw new \Exception('Insufficient wallet balance');
            }

            // Deduct from wallet
            $transaction = $this->walletService->debit($user, $consultation->consultation_fee);

            // Update payment data
            $consultation->payment_status = 'paid';
            $consultation->payment_reference = $transaction->transaction_number;
            $consultation->payment_method = 'wallet';

            // update status to scheduled
            $consultation->save();
            DB::commit();
            return $consultation;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateMedia(BkamConsultation $bkamConsultation, array $data): BkamConsultation
    {
        $keep_media = $data['keep_media'] ?? [];
        $bkamConsultation->media()->whereNotIn('id', $keep_media)->delete();

        $mediaCollections = [
            'media_ids' => 'bekam_consultations',
            'before_media_ids' => 'before',
            'after_media_ids' => 'after'
        ];

        foreach ($mediaCollections as $mediaKey => $collectionName) {
            if (!empty($data[$mediaKey])) {
                Media::whereIn('id', $data[$mediaKey])
                    ->update([
                        'model_type' => 'App\Models\BkamConsultation',
                        'model_id' => $bkamConsultation->id,
                        'collection_name' => $collectionName
                    ]);
            }
        }

        $bkamConsultation->load('media');

        return $bkamConsultation;
    }

    public function delete($id, $userId)
    {
        $consultation = $this->bkamConsultationRepository->getOneOrFail($id);
        if ($consultation->user_id !== $userId) {
            abort(403, 'You are not authorized to delete this consultation');
        }
        $this->bkamConsultationRepository->delete($id);
    }
}