<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class NotificationMigrationHelper
{
    protected $unifiedService;

    public function __construct(UnifiedNotificationService $unifiedService)
    {
        $this->unifiedService = $unifiedService;
    }

    /**
     * Send a product notification using the unified service
     */
    public function sendProductNotification($product, $notifiable)
    {
        $productType = $product->type->value;
        $type = $productType === 'product' ? 'منتج' : 'إجراء';
        $summary = "تم إضافة {$type} جديد بواسطة {$product->vendor->name}";

        return $this->unifiedService->send(
            module: "new-{$productType}",
            title: "New {$productType}",
            summary: $summary,
            recipientId: $notifiable->id,
            data: [
                'product_id' => $product->id,
                'vendor_id' => $product->vendor->id,
                'image' => $product?->image?->url ?? '',
            ]
        );
    }

    /**
     * Send an order notification using the unified service
     */
    public function sendOrderNotification($order, $notifiable, $isUpdate = false)
    {
        $module = $isUpdate ? 'update-order' : 'new-order';
        $title = $isUpdate ? 'Order Updated' : 'New Order';
        $summary = $isUpdate ? 'تم تحديث حالة الطلب' : 'لديك طلب جديد';

        return $this->unifiedService->send(
            module: $module,
            title: $title,
            summary: $summary,
            recipientId: $notifiable->id,
            data: [
                'order_id' => $order->id,
                'vendor_id' => $order->vendor_id ?? null,
                'status' => $order->status ?? null,
            ]
        );
    }

    /**
     * Send an offer notification using the unified service
     */
    public function sendOfferNotification($offer, $notifiable)
    {
        return $this->unifiedService->send(
            module: 'new-offer',
            title: $offer->product->title,
            summary: "عرض جديد على {$offer->product->title}",
            recipientId: $notifiable->id,
            data: [
                'offer_id' => $offer->id,
                'product_id' => $offer->product_id,
                'discount' => $offer->discount_string ?? '',
                'image' => $offer->product?->image?->url ?? '',
            ]
        );
    }

    /**
     * Send a review notification using the unified service
     */
    public function sendReviewNotification($order, $notifiable)
    {
        return $this->unifiedService->send(
            module: 'new-review',
            title: 'تقييم جديد',
            summary: "تم تقييم الطلب رقم {$order->id}",
            recipientId: $notifiable->id,
            data: [
                'order_id' => $order->id,
                'review_id' => $order->review->id ?? null,
            ]
        );
    }

    /**
     * Send a support message notification using the unified service
     */
    public function sendSupportMessageNotification($supportMessage, $notifiable)
    {
        return $this->unifiedService->send(
            module: 'support-message',
            title: 'Support Message',
            summary: 'You have a new reply to your support ticket.',
            recipientId: $notifiable->id,
            data: [
                'support_message_id' => $supportMessage->id,
                'parent_id' => $supportMessage->parent_id,
            ]
        );
    }

    /**
     * Send notifications to multiple recipients
     */
    public function sendToMultiple(string $module, string $title, string $summary, array $data, $notifiables, ?int $adminId = null): array
    {
        $recipientIds = [];

        if ($notifiables instanceof Collection) {
            $recipientIds = $notifiables->pluck('id')->toArray();
        } elseif (is_array($notifiables)) {
            $recipientIds = collect($notifiables)->map(function ($item) {
                return $item instanceof Model ? $item->id : $item;
            })->toArray();
        }

        return $this->unifiedService->sendToMultiple(
            $module,
            $title,
            $summary,
            $data,
            $recipientIds,
            $adminId
        );
    }
}
