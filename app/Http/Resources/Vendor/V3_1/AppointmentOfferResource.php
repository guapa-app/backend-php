<?php

namespace App\Http\Resources\Vendor\V3_1;


use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentOfferResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'vendor' => VendorResource::make($this->whenLoaded('vendor')),
            'taxonomy' => TaxonomyResource::make($this->whenLoaded('taxonomy')),
            'status' => $this->status,
            'notes' => $this->notes,
            'details' => AppointmentOfferDetailsResource::collection($this->whenLoaded('details')),
            'appointment_form' => AppointmentFormResource::collection($this->whenLoaded('appointmentForms')),
            'images' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
