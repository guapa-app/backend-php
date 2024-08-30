<?php

namespace App\Http\Resources;

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
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'channel' => $this->channel,
            'audience_type' => $this->audience_type,
            'audience_count' => $this->audience_count,
            'message_cost' => $this->message_cost,
            'taxes' => $this->taxes,
            'total_cost' => $this->total_cost,
            'status' => $this->status,
            'invoice_url' => $this->invoice_url,
            'offer' => $this->when($this->campaignable_type == 'offer', new OfferResource($this->campaignable)),
            'product' => $this->when($this->campaignable_type == 'product', new ProductResource($this->campaignable)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
