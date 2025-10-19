<?php

namespace App\Http\Resources\Vendor\V3_1;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'hash_id' => (string) $this->hash_id,
            'total' => (float) $this->total,
            'paid_amount_with_taxes' => (float) $this->paid_amount_with_taxes,
            'paid_amount' => (float) $this->paid_amount,
            'remaining_amount' => (float) $this->remaining_amount,
            'status' => $this->status,
            'type' => $this->type,

            'name'        => (string) $this->user?->name,
            'phone'       => (string) $this->user?->phone,

            'invoice_url' => (string) $this->invoice_url,
            'cancellation_reason' => (string) $this->cancellation_reason,

            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
            'updated_at' => Carbon::parse($this->updated_at)->diffForHumans(),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'address' => AddressResource::make($this->whenLoaded('address')),
            'vendor' => VendorResource::make($this->whenLoaded('vendor')),
            'staff' => UserResource::make($this->whenLoaded('staff')),
            'appointments' => AppointmentFormResource::collection($this->whenLoaded('appointments')),
        ];
    }
}
