<?php

namespace App\Http\Resources\Vendor\V3_1;


use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentOfferResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'taxonomy' => TaxonomyResource::make($this->whenLoaded('taxonomy')),
            'status' => $this->status,
            'notes' => $this->notes,
            'created_date' => $this->created_at->format('Y-m-d'),
            'details' => AppointmentOfferDetailsResource::make($this->whenLoaded('details')->first()),
            'appointment_form' => AppointmentFormResource::collection($this->whenLoaded('appointmentForms')),
            'images' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
