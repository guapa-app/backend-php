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
        $productType = $product->type->value ?? 'product';

        return $this->unifiedService->send(
            module: "new-{$productType}",
            title: "New {$productType}",
            summary: "منتج جديد: {$product->title}",
            recipientId: $notifiable->id,
            data: [
                'product_id' => $product->id,
                'title' => $product->title,
                'type' => $productType,
                'image' => $product?->image?->url ?? '',
                'vendor_name' => $product->vendor->business_name ?? ''
            ]
        );
    }

    /**
     * Send an order notification using the unified service
     */
    public function sendOrderNotification($order, $notifiable, $isVendor = false)
    {
        $module = $isVendor ? 'new-order' : 'update-order';
        $title = $isVendor ? 'New Order Received' : 'Order Update';

        if ($isVendor) {
            $summary = "New order #{$order->id} from {$order->user->name}";
        } else {
            $summary = "Your order #{$order->id} status has been updated to {$order->status}";
        }

        return $this->unifiedService->send(
            module: $module,
            title: $title,
            summary: $summary,
            recipientId: $notifiable->id,
            data: [
                'order_id' => $order->id,
                'status' => $order->status ?? 'pending',
                'customer_name' => $order->user->name ?? '',
                'vendor_name' => $order->vendor->business_name ?? '',
                'total_amount' => $order->total ?? 0,
                'type' => $order->type ?? 'product'
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
     * Send a chat message notification using the unified service
     */
    public function sendChatMessageNotification($message, $notifiable)
    {
        return $this->unifiedService->send(
            module: 'message',
            title: $message->sender->name ?? 'New Message',
            summary: $this->getChatMessageSummary($message),
            recipientId: $notifiable->id,
            data: [
                'message_id' => $message->id,
                'conversation_id' => $message->conversation_id,
                'sender_id' => $message->sender_id,
                'type' => $message->type ?? 'text',
                'is_offer' => $message->type === 'offer'
            ]
        );
    }

    /**
     * Send a like notification using the unified service
     */
    public function sendLikeNotification($user, $likeable, $notifiable)
    {
        return $this->unifiedService->send(
            module: 'new-like',
            title: 'New Like',
            summary: "أعجب {$user->name} بمنشورك",
            recipientId: $notifiable->id,
            data: [
                'liker_id' => $user->id,
                'liker_name' => $user->name,
                'likeable_type' => get_class($likeable),
                'likeable_id' => $likeable->id
            ]
        );
    }

    /**
     * Send invoice notification using the unified service
     */
    public function sendInvoiceNotification($order, $notifiable, $type = 'user')
    {
        $module = $type === 'admin' ? 'admin-invoice' : 'invoice';
        $title = $type === 'admin' ? 'New Invoice Created' : 'Invoice Ready';
        $summary = $type === 'admin'
            ? "Invoice created for order #{$order->id}"
            : "Your invoice for order #{$order->id} is ready";

        return $this->unifiedService->send(
            module: $module,
            title: $title,
            summary: $summary,
            recipientId: is_string($notifiable) ? 0 : $notifiable->id, // Handle phone numbers
            data: [
                'order_id' => $order->id,
                'invoice_url' => $order->invoice_url ?? '',
                'total_amount' => $order->total ?? 0,
                'recipient_type' => $type
            ]
        );
    }

    /**
     * Send consultation notification using the unified service
     */
    public function sendConsultationNotification($consultation, $notifiable, $type = 'invitation')
    {
        $modules = [
            'invitation' => 'consultation-invitation',
            'cancelled' => 'consultation-cancelled',
            'meeting' => 'meeting-invitation'
        ];

        $titles = [
            'invitation' => 'Consultation Invitation',
            'cancelled' => 'Consultation Cancelled',
            'meeting' => 'Meeting Invitation'
        ];

        return $this->unifiedService->send(
            module: $modules[$type] ?? 'consultation',
            title: $titles[$type] ?? 'Consultation Update',
            summary: $this->getConsultationSummary($consultation, $type),
            recipientId: $notifiable->id,
            data: [
                'consultation_id' => $consultation->id,
                'type' => $type,
                'vendor_name' => $consultation->vendor->business_name ?? '',
                'user_name' => $consultation->user->name ?? ''
            ]
        );
    }

    /**
     * Send payout notification using the unified service
     */
    public function sendPayoutNotification($transaction, $notifiable)
    {
        return $this->unifiedService->send(
            module: 'payout-status',
            title: 'Payout Update',
            summary: "Payout status updated: {$transaction->status}",
            recipientId: $notifiable->id,
            data: [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'status' => $transaction->status,
                'reference' => $transaction->reference ?? ''
            ]
        );
    }

    /**
     * Send appointment offer notification using the unified service
     */
    public function sendAppointmentOfferNotification($appointmentOffer, $notifiable)
    {
        return $this->unifiedService->send(
            module: 'appointment-offer',
            title: 'موعد جديد متاح',
            summary: "عرض موعد جديد من {$appointmentOffer->vendor->business_name}",
            recipientId: $notifiable->id,
            data: [
                'appointment_offer_id' => $appointmentOffer->id,
                'vendor_id' => $appointmentOffer->vendor_id,
                'vendor_name' => $appointmentOffer->vendor->business_name ?? '',
                'date' => $appointmentOffer->date ?? '',
                'time' => $appointmentOffer->time ?? ''
            ]
        );
    }

    /**
     * Send push notification (general) using the unified service
     */
    public function sendPushNotification($title, $summary, $notifiable, $data = [])
    {
        return $this->unifiedService->send(
            module: 'push-notifications',
            title: $title,
            summary: $summary,
            recipientId: $notifiable->id,
            data: array_merge($data, [
                'type' => 'push-notifications'
            ])
        );
    }

    /**
     * Send notifications to multiple recipients
     */
    public function sendToMultiple(string $module, string $title, string $summary, array $data, $notifiables, ?int $adminId = null): array
    {
        $recipientIds = collect($notifiables)->map(function ($notifiable) {
            return is_object($notifiable) ? $notifiable->id : (int)$notifiable;
        })->filter()->toArray();

        return $this->unifiedService->sendToMultiple(
            module: $module,
            title: $title,
            summary: $summary,
            recipientIds: $recipientIds,
            data: $data,
            adminId: $adminId
        );
    }

    /**
     * Helper method to get chat message summary
     */
    protected function getChatMessageSummary($message): string
    {
        if ($message->type === 'offer') {
            return 'عرض جديد في المحادثة';
        }

        return $message->content ?? 'رسالة جديدة';
    }

    /**
     * Helper method to get consultation summary
     */
    protected function getConsultationSummary($consultation, $type): string
    {
        switch ($type) {
            case 'invitation':
                return "دعوة لاستشارة مع {$consultation->vendor->business_name}";
            case 'cancelled':
                return "تم إلغاء الاستشارة مع {$consultation->vendor->business_name}";
            case 'meeting':
                return "دعوة لاجتماع - {$consultation->vendor->business_name}";
            default:
                return "تحديث الاستشارة";
        }
    }

    /**
     * Send offer status change notification using the unified service
     */
    public function sendOfferStatusNotification($message, $status, $notifiable)
    {
        $statusMessages = [
            'accepted' => 'تم قبول عرضك',
            'rejected' => 'تم رفض عرضك',
            'canceled' => 'تم إلغاء العرض'
        ];

        $title = 'Offer Status Update';
        $summary = $statusMessages[$status] ?? 'تم تحديث حالة العرض';

        return $this->unifiedService->send(
            module: 'offer-status',
            title: $title,
            summary: $summary,
            recipientId: $notifiable->id,
            data: [
                'message_id' => $message->id,
                'conversation_id' => $message->conversation_id,
                'status' => $status,
                'offer_data' => is_string($message->message) ? json_decode($message->message, true) : $message->message
            ]
        );
    }
}
