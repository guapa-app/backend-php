<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'total'       => (double)$this->total,
            'status'      => $this->status,
            'note'        => (string)$this->note,
            'name'        => (string)$this->name,

            'phone'       => (string)$this->phone,
            'is_used'     => (bool)$this->is_used,
            'invoice_url' => (string)$this->invoice_url,

            'cancellation_reason' => (string)$this->cancellation_reason,

            'created_at'  => Carbon::parse($this->created_at)->diffForHumans(),
            'updated_at'  => Carbon::parse($this->updated_at)->diffForHumans(),

            'items'       => OrderItemResource::collection($this->whenLoaded('items')),
            'address'     => AddressResource::make($this->whenLoaded('address')),
            'user'        => UserResource::make($this->whenLoaded('user')),
            'vendor'      => VendorResource::make($this->whenLoaded('vendor')),
        ];
    }
}
