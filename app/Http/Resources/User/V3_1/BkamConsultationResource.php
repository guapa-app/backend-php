<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BkamConsultationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'consultation_reason' => $this->consultation_reason,
            'medical_history' => $this->medical_history,
            'consultation_fee' => (float) $this->consultation_fee,
            'taxes' => (float) $this->taxes,
            'invoice_url' => $this->invoice_url,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'payment_reference' => $this->payment_reference,
            'can_cancel' => $this->canCancel(),
            'media' => MediaResource::collection($this->whenLoaded('media')),
            'cancelled_at' => $this->cancelled_at ? $this->cancelled_at->format('Y-m-d H:i:s') : null,
            'rejected_at' => $this->rejected_at ? $this->rejected_at->format('Y-m-d H:i:s') : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
