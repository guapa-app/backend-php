<?php

namespace App\Http\Resources\User\V3_1;

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

            'background_image_details' => $this->whenLoaded('backgroundImage', function () {
                return [
                    'id' => $this->backgroundImage->id,
                    'name' => $this->backgroundImage->name,
                    'description' => $this->backgroundImage->description,
                    'image_url' => $this->backgroundImage->image_url,
                    'thumbnail_url' => $this->backgroundImage->thumbnail_url,
                ];
            }),

            // Computed fields
            'display_name' => $this->display_name,
            'display_email' => $this->display_email,
            'display_phone' => $this->display_phone,
            'is_wallet_type' => $this->isWalletType(),
            'is_order_type' => $this->isOrderType(),
            'is_redeemed' => $this->isRedeemed(),
            'is_expired' => $this->isExpired(),
            'can_be_redeemed' => $this->canBeRedeemed(),
        ];
    }
}
