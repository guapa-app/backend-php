<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GiftCardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'amount' => (float) $this->amount,
            'currency' => $this->currency,
            'tax_amount' => (float) $this->tax_amount,
            'total_amount' => (float) $this->total_amount,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'payment_status' => $this->payment_status,
            'payment_status_label' => $this->payment_status_label,
            'gift_type' => $this->gift_type,
            'gift_type_label' => $this->gift_type_label,
            'redemption_method' => $this->redemption_method,
            'message' => $this->message,
            'notes' => $this->notes,
            'expires_at' => $this->expires_at?->toISOString(),
            'redeemed_at' => $this->redeemed_at?->toISOString(),
            'background_color' => $this->background_color,
            'background_image_url' => $this->background_image_url,
            'background_thumbnail_url' => $this->background_thumbnail_url,
            'recipient_name' => $this->recipient_name,
            'recipient_email' => $this->recipient_email,
            'recipient_number' => $this->recipient_number,
            'payment_method' => $this->payment_method,
            'payment_reference' => $this->payment_reference,
            'payment_gateway' => $this->payment_gateway,
            'invoice_url' => $this->invoice_url,
            'redemption_url' => $this->buildRedemptionUrl(),

            // QR Code URLs
            'qr_code_url' => $this->qr_code_url,
            'qr_code_data_url' => $this->qr_code_data_url,

            // Relationships
            'sender' => UserResource::make($this->whenLoaded('sender')),
            'recipient' => UserResource::make($this->whenLoaded('recipient')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'vendor' => VendorResource::make($this->whenLoaded('vendor')),
            'product' => ProductResource::make($this->whenLoaded('product')),
            'offer' => OfferResource::make($this->whenLoaded('offer')),
            'order' => OrderResource::make($this->whenLoaded('order')),
            'wallet_transaction' => TransactionResource::make($this->whenLoaded('walletTransaction')),
            'background_image' => GiftCardBackgroundResource::make($this->whenLoaded('backgroundImage')),

            // Computed properties
            'is_wallet_type' => $this->isWalletType(),
            'is_order_type' => $this->isOrderType(),
            'is_redeemed' => $this->isRedeemed(),
            'is_expired' => $this->isExpired(),
            'can_be_redeemed' => $this->canBeRedeemed(),
            'is_payment_pending' => $this->isPaymentPending(),
            'is_payment_paid' => $this->isPaymentPaid(),
            'is_payment_failed' => $this->isPaymentFailed(),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
