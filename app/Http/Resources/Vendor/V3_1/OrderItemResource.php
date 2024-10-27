<?php

namespace App\Http\Resources\Vendor\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'coupon_num' => (string) $this->coupon_num,
            'amount' => (float) $this->amount,
            'title' => (float) $this->title,
            'amount_to_pay' => (float) $this->amount_to_pay,
            'taxes' => (float) $this->taxes,
            'quantity' => (int) $this->quantity,
            'order_id' => $this->order_id,
            'appointment' => $this->appointment,
            'qr_code_link' => $this->qr_code_link,
            'product' => ProductResource::make($this->whenLoaded('product')),
        ];
    }
}
