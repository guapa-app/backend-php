<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id"           => $this->id,
            "coupon_num"   => (string)$this->coupon_num,
            "amount"       => (double)$this->amount,
            "quantity"     => (int)$this->quantity,
            "order_id"     => $this->order_id,
            "appointment"  => $this->appointment,
            "qr_code_link" => $this->qr_code_link,

            "product"      => ProductResource::make($this->whenLoaded('product')),
//            "offer"        => OfferResource::make($this->whenLoaded('offer')),
            "vendor"       => VendorResource::make($this->whenLoaded('vendor')),
        ];
    }
}
