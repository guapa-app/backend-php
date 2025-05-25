<?php

namespace App\Services;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Services\UnifiedNotificationService;
use Illuminate\Support\Facades\Log;

/**
 * NotificationInterceptor - Automatically routes old Laravel notifications through the unified service
 * 
 * This service can intercept and convert traditional Laravel notifications to use the external service
 * without requiring manual migration of each notification call.
 */
class NotificationInterceptor
{
    protected $unifiedService;
    protected $notificationMapping;

    public function __construct(UnifiedNotificationService $unifiedService)
    {
        $this->unifiedService = $unifiedService;
        $this->initializeNotificationMapping();
    }

    /**
     * Initialize mapping from old notification classes to unified service modules
     */
    protected function initializeNotificationMapping()
    {
        $this->notificationMapping = [
            // Order notifications
            'App\Notifications\OrderNotification' => 'new-order',
            'App\Notifications\OrderUpdatedNotification' => 'update-order',

            // Product notifications  
            'App\Notifications\ProductNotification' => 'new-product',
            'App\Notifications\OfferNotification' => 'new-offer',

            // Communication notifications
            'App\Notifications\ChatMessage' => 'message',
            'App\Notifications\ReplySupportMessageNotification' => 'support-message',
            'App\Notifications\NewCommentNotification' => 'comments',

            // Financial notifications
            'App\Notifications\InvoiceNotification' => 'invoice',
            'App\Notifications\VendorInvoiceNotification' => 'invoice',
            'App\Notifications\AdminInvoiceNotification' => 'admin-invoice',
            'App\Notifications\PayoutStatusNotification' => 'payout-status',

            // Consultation notifications
            'App\Notifications\ConsultationInvitationNotification' => 'consultation-invitation',
            'App\Notifications\ConsultationCancelled' => 'consultation-cancelled',
            'App\Notifications\MeetingInvitationNotification' => 'meeting-invitation',

            // Social notifications
            'App\Notifications\NewLikeNotification' => 'new-like',
            'App\Notifications\NewReviewNotification' => 'new-review',

            // General notifications
            'App\Notifications\PushNotification' => 'push-notifications',
            'App\Notifications\CampaignNotification' => 'campaign',
            'App\Notifications\AppointmentOfferNotification' => 'appointment-offer',
            'App\Notifications\OfferStatusChanged' => 'offer-status',
            'App\Notifications\PendingOrderReminderNotification' => 'order-reminder',
            'App\Notifications\AddVendorClientNotification' => 'vendor-client',
        ];
    }

    /**
     * Intercept single notification and route through unified service
     * 
     * Usage: Replace app(\App\Services\NotificationInterceptor::class)->interceptSingle($$user, $$notification) with 
     * $interceptor->interceptSingle($user, $notification)
     */
    public function interceptSingle($notifiable, Notification $notification): bool
    {
        $notificationClass = get_class($notification);

        if (!isset($this->notificationMapping[$notificationClass])) {
            Log::warning("Unknown notification class for interception: {$notificationClass}");
            return false;
        }

        $module = $this->notificationMapping[$notificationClass];

        try {
            // Extract data from the notification
            $data = $this->extractNotificationData($notification, $notifiable);

            return $this->unifiedService->send(
                module: $module,
                title: $data['title'],
                summary: $data['summary'],
                recipientId: $notifiable->id,
                data: $data['data']
            );
        } catch (\Exception $e) {
            Log::error("Failed to intercept notification: {$e->getMessage()}", [
                'notification_class' => $notificationClass,
                'notifiable_id' => $notifiable->id ?? 'unknown',
                'module' => $module
            ]);
            return false;
        }
    }

    /**
     * Intercept bulk notifications and route through unified service
     * 
     * Usage: Replace app(\App\Services\NotificationInterceptor::class)->interceptBulk($$users, $$notification) with
     * $interceptor->interceptBulk($users, $notification)
     */
    public function interceptBulk($notifiables, Notification $notification): array
    {
        $notificationClass = get_class($notification);

        if (!isset($this->notificationMapping[$notificationClass])) {
            Log::warning("Unknown notification class for bulk interception: {$notificationClass}");
            return [];
        }

        $module = $this->notificationMapping[$notificationClass];

        // Convert notifiables to recipient IDs
        $recipientIds = collect($notifiables)->map(function ($notifiable) {
            if (is_string($notifiable)) {
                return 0; // Handle phone numbers/emails - will need special handling
            }
            return is_object($notifiable) ? $notifiable->id : (int)$notifiable;
        })->filter()->toArray();

        if (empty($recipientIds)) {
            Log::warning("No valid recipient IDs found for bulk notification");
            return [];
        }

        try {
            // Extract data from the notification (using first notifiable for context)
            $firstNotifiable = is_array($notifiables) ? $notifiables[0] : $notifiables->first();
            $data = $this->extractNotificationData($notification, $firstNotifiable);

            $result = $this->unifiedService->sendToMultiple(
                module: $module,
                title: $data['title'],
                summary: $data['summary'],
                recipientIds: $recipientIds,
                data: $data['data']
            );

            return is_array($result) ? $result : [$result];
        } catch (\Exception $e) {
            Log::error("Failed to intercept bulk notification: {$e->getMessage()}", [
                'notification_class' => $notificationClass,
                'recipient_count' => count($recipientIds),
                'module' => $module
            ]);
            return [];
        }
    }

    /**
     * Extract notification data from various notification types
     */
    protected function extractNotificationData(Notification $notification, $notifiable): array
    {
        $data = [
            'title' => 'Notification',
            'summary' => 'You have a new notification',
            'data' => []
        ];

        // Try to get data using common methods
        try {
            // Try toArray method (most notifications have this)
            if (method_exists($notification, 'toArray')) {
                $arrayData = call_user_func([$notification, 'toArray'], $notifiable);
                if (is_array($arrayData)) {
                    $data['title'] = $arrayData['title'] ?? $data['title'];
                    $data['summary'] = $arrayData['summary'] ?? $arrayData['body'] ?? $data['summary'];
                    $data['data'] = $arrayData;
                }
            }

            // Try toFirebase method (for push notifications)
            if (method_exists($notification, 'toFirebase')) {
                $firebaseData = call_user_func([$notification, 'toFirebase'], $notifiable);
                if (is_array($firebaseData)) {
                    $data['title'] = $firebaseData['title'] ?? $data['title'];
                    $data['summary'] = $firebaseData['body'] ?? $data['summary'];
                }
            }

            // Try toMail method (for email notifications)
            if (method_exists($notification, 'toMail')) {
                $mailData = call_user_func([$notification, 'toMail'], $notifiable);
                if (is_object($mailData)) {
                    $data['title'] = $mailData->subject ?? $data['title'];
                    $data['summary'] = $mailData->greeting ?? $data['summary'];
                }
            }

            // Specific extraction for known notification types
            $this->extractSpecificNotificationData($notification, $data, $notifiable);
        } catch (\Exception $e) {
            Log::warning("Failed to extract notification data: {$e->getMessage()}");
        }

        return $data;
    }

    /**
     * Extract data from specific notification types using reflection
     */
    protected function extractSpecificNotificationData(Notification $notification, array &$data, $notifiable): void
    {
        $notificationClass = get_class($notification);

        try {
            $reflection = new \ReflectionClass($notification);

            // Try to get common properties
            $titleFound = false;
            $summaryFound = false;

            foreach (['title', 'subject'] as $prop) {
                if (!$titleFound && $reflection->hasProperty($prop)) {
                    $property = $reflection->getProperty($prop);
                    $property->setAccessible(true);
                    $value = $property->getValue($notification);
                    if (!empty($value)) {
                        $data['title'] = $value;
                        $titleFound = true;
                    }
                }
            }

            foreach (['summary', 'body', 'message'] as $prop) {
                if (!$summaryFound && $reflection->hasProperty($prop)) {
                    $property = $reflection->getProperty($prop);
                    $property->setAccessible(true);
                    $value = $property->getValue($notification);
                    if (!empty($value)) {
                        $data['summary'] = $value;
                        $summaryFound = true;
                    }
                }
            }

            // Extract related model data
            foreach (['order', 'product', 'message', 'offer', 'consultation'] as $prop) {
                if ($reflection->hasProperty($prop)) {
                    $property = $reflection->getProperty($prop);
                    $property->setAccessible(true);
                    $value = $property->getValue($notification);
                    if ($value && is_object($value)) {
                        $data['data'][$prop . '_id'] = $value->id ?? null;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::debug("Could not extract specific notification data: {$e->getMessage()}");
        }
    }

    /**
     * Check if a notification class is supported for interception
     */
    public function canIntercept(string $notificationClass): bool
    {
        return isset($this->notificationMapping[$notificationClass]);
    }

    /**
     * Get all supported notification classes
     */
    public function getSupportedNotifications(): array
    {
        return array_keys($this->notificationMapping);
    }

    /**
     * Add custom notification mapping
     */
    public function addNotificationMapping(string $notificationClass, string $module): void
    {
        $this->notificationMapping[$notificationClass] = $module;
    }
}
