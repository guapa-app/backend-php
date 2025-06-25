<?php

namespace App\Http\Resources\Admin\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class GiftCardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'gift_type' => $this->gift_type,
            'gift_type_label' => $this->gift_type_label,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'background_color' => $this->background_color,
            'background_image' => $this->background_image_url,
            'background_image_id' => $this->background_image_id,
            'message' => $this->message,
            'notes' => $this->notes,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'redemption_method' => $this->redemption_method,
            'expires_at' => $this->expires_at,
            'redeemed_at' => $this->redeemed_at,
            'recipient_name' => $this->recipient_name,
            'recipient_email' => $this->recipient_email,
            'recipient_number' => $this->recipient_number,
            'user_id' => $this->user_id,
            'vendor_id' => $this->vendor_id,
            'product_id' => $this->product_id,
            'offer_id' => $this->offer_id,
            'order_id' => $this->order_id,
            'wallet_transaction_id' => $this->wallet_transaction_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relationships
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'phone' => $this->user->phone,
                ];
            }),

            'vendor' => $this->whenLoaded('vendor', function () {
                return [
                    'id' => $this->vendor->id,
                    'name' => $this->vendor->name,
                ];
            }),

            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'title' => $this->product->title,
                    'price' => $this->product->price,
                ];
            }),

            'offer' => $this->whenLoaded('offer', function () {
                return [
                    'id' => $this->offer->id,
                    'title' => $this->offer->title,
                    'price' => $this->offer->price,
                ];
            }),

            'order' => $this->whenLoaded('order', function () {
                return [
                    'id' => $this->order->id,
                    'status' => $this->order->status,
                    'total_amount' => $this->order->total_amount,
                    'created_at' => $this->order->created_at,
                ];
            }),

            'wallet_transaction' => $this->whenLoaded('walletTransaction', function () {
                return [
                    'id' => $this->walletTransaction->id,
                    'amount' => $this->walletTransaction->amount,
                    'type' => $this->walletTransaction->type,
                    'status' => $this->walletTransaction->status,
                    'created_at' => $this->walletTransaction->created_at,
                ];
            }),

            'background_image_data' => $this->whenLoaded('backgroundImage', function () {
                return [
                    'id' => $this->backgroundImage->id,
                    'name' => $this->backgroundImage->name,
                    'description' => $this->backgroundImage->description,
                    'image_url' => $this->backgroundImage->image_url,
                    'thumbnail_url' => $this->backgroundImage->thumbnail_url,
                ];
            }),

            'created_by_user' => $this->whenLoaded('createdBy', function () {
                return [
                    'id' => $this->createdBy->id,
                    'name' => $this->createdBy->name,
                    'email' => $this->createdBy->email,
                ];
            }),

            // Computed fields
            'is_expired' => $this->isExpired(),
            'can_be_redeemed' => $this->canBeRedeemed(),
            'days_until_expiry' => $this->expires_at ? now()->diffInDays($this->expires_at, false) : null,
            'formatted_amount' => $this->currency . ' ' . number_format($this->amount, 2),
        ];
    }
}