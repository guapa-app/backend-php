<?php

namespace App\Http\Resources\Vendor\V3_1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarketingCampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => (int) $this->id,
            'vendor_id'      => (int) $this->vendor_id,
            'channel'        => $this->channel,
            'audience_type'  => $this->audience_type,
            'audience_count' => (int) $this->audience_count,
            'message_cost'   => (float) $this->message_cost,
            'taxes'          => (float) $this->taxes,
            'total_cost'     => (float) $this->total_cost,
            'status'         => $this->status,
            'invoice_url'    => (string) $this->invoice_url,
            'offer'          => $this->when($this->campaignable_type == 'offer', new ProductResource($this->campaignable->product)),
            'product'        => $this->when($this->campaignable_type == 'product', new ProductResource($this->campaignable)),
            'created_at'     => (string) $this->created_at,
            'updated_at'     => (string) $this->updated_at,
        ];
    }
}
