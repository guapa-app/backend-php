<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'code' => $this->code,
            'status' => $this->IsActive() ? __('api.coupon_statuses.active') : __('api.coupon_statuses.expired'),
            'discount_percentage' => $this->discount_percentage,
            'expires_at' => $this->expires_at ? $this->expires_at->toDateTimeString() : null,
            'usage_count' => $this->total_usage_count,
            'max_uses' => $this->max_uses,
            'single_user_usage' => $this->single_user_usage,
            'products' => $this->whenLoaded('products', function () {
                return $this->products->map(function ($product) {
                    return [
                        'title' => $product->title,
                    ];
                });
            }),
            'created_at' => $this->created_at->toDateTimeString(),
            ];
    }
}
