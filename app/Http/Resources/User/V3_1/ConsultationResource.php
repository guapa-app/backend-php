<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ConsultationResource extends JsonResource
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
            'vendor_id' => $this->vendor_id,
            'appointment_date' => $this->appointment_date->format('Y-m-d'),
            'appointment_time' => $this->appointment_time->format('H:i A'),
            'status' => $this->status,
            'consultation_reason' => $this->consultation_reason,
            'medical_history' => $this->medical_history,
            'consultation_fee' => (float) $this->consultation_fee,
            'application_fees' => (float) $this->application_fees,
            'tax_amount' => (float) $this->tax_amount,
            'total_amount' => (float) $this->total_amount,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'payment_reference' => $this->payment_reference,
            'session_url' => $this->session_url,
            'session_password' => $this->when($this->session_password, $this->session_password),
            'meeting_provider' => $this->meeting_provider,
            'can_cancel' => $this->canCancel(),
            'can_join' => $this->canJoin(),
            'user' => new UserResource($this->whenLoaded('user')),
            'vendor' => new VendorResource($this->whenLoaded('vendor')),
            'media' => MediaResource::collection($this->whenLoaded('media')),
            'cancelled_at' => $this->cancelled_at ? $this->cancelled_at->format('Y-m-d H:i:s') : null,
            'rejected_at' => $this->rejected_at ? $this->rejected_at->format('Y-m-d H:i:s') : null,
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
