<?php

namespace App\Http\Resources\User\V3_1;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'hash_id' => (string) $this->hash_id,
            'total' => (float) $this->total,
            'paid_amount_with_taxes' => (float) $this->invoice?->paid_amount_with_taxes ?? 0,
            'paid_amount' => (float) $this->invoice?->paid_amount ?? 0,
            'remaining_amount' => (float) ($this->total - $this->invoice?->paid_amount),
            'status' => $this->status,
            'type' => $this->type,
            'invoice_url' => (string) $this->invoice_url,
            'cancellation_reason' => (string) $this->cancellation_reason,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
            'updated_at' => Carbon::parse($this->updated_at)->diffForHumans(),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'address' => AddressResource::make($this->whenLoaded('address')),
            'vendor' => VendorResource::make($this->whenLoaded('vendor')),
            'staff' => UserResource::make($this->whenLoaded('staff')),
            'appointment' => AppointmentOfferDetailsResource::make($this->whenLoaded('appointmentOfferDetails')),
        ];
    }
}
