<?php

namespace App\Services\V3_1;

use App\Enums\BkamConsultationStatus;
use App\Models\BkamConsultation;
use App\Models\Media;
use App\Models\Setting;
use DB;

class BkamConsultationService
{

    /**
     * Create a new bkam consultation
     *
     * @param array $data
     * @param \App\Models\User $user
     * @return \App\Models\BkamConsultation
     */
    public function createConsultation(array $data, $user)
    {
        DB::beginTransaction();

        try {
            // Add user ID to data
            $data['user_id'] = $user->id;

            // Set initial status as pending
            $data['status'] = BkamConsultationStatus::STATUS_PENDING;
            $data['consultation_fees'] = Setting::getBkamConsultationFee();
            $data['payment_status'] = 'pending';

            // Create consultation
            $consultation = BkamConsultation::create($data);

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
}