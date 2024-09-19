<?php

namespace App\Http\Resources\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentOfferDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'appointmentOffer' => AppointmentOfferResource::make($this->whenLoaded('appointmentOffer')),
            'sub_vendor' => VendorResource::make($this->whenLoaded('subVendor')),
            'status' => $this->status,
            'offer_price' => (float) $this->offer_price,
            'reject_reason' => $this->reject_reason,
            'staff_notes' => $this->staff_notes,
            'offer_notes' => $this->offer_notes,
            'terms' => $this->terms,
            'starts_at' => $this->starts_at,
            'expires_at' => $this->expires_at
        ];
    }
}
