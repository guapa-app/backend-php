<?php

namespace App\Http\Resources\Vendor\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class GiftCardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'background_color' => $this->background_color,
            'background_image' => $this->background_image,
            'message' => $this->message,
            'status' => $this->status,
            'expires_at' => $this->expires_at,
            'redeemed_at' => $this->redeemed_at,
            'recipient_name' => $this->recipient_name,
            'recipient_email' => $this->recipient_email,
            'recipient_number' => $this->recipient_number,
            'user_id' => $this->user_id,
            'vendor_id' => $this->vendor_id,
            'product_id' => $this->product_id,
            'offer_id' => $this->offer_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
