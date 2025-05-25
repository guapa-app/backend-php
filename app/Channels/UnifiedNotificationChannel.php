<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\UnifiedNotificationService;
use App\Services\NotificationChannelResolver;
use Illuminate\Support\Facades\Log;
use ReflectionClass;

/**
 * UnifiedNotificationChannel - Automatically intercepts all Laravel notifications
 * 
 * This channel can be used to automatically route ALL Laravel notifications through
 * the external service without modifying existing notification calls.
 * 
 * To enable, add this channel to all your notifications or set it as default.
 */
class UnifiedNotificationChannel
{
    protected $unifiedService;
    protected $channelResolver;

    public function __construct(
        UnifiedNotificationService $unifiedService,
        NotificationChannelResolver $channelResolver
    ) {
        $this->unifiedService = $unifiedService;
        $this->channelResolver = $channelResolver;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return bool
     */
    public function send($notifiable, Notification $notification): bool
    {
        try {
            // Extract module from notification class
            $module = $this->extractModule($notification);

            // Extract notification data
            $data = $this->extractNotificationData($notification, $notifiable);

            // Handle multiple recipients (for bulk sends)
            if (is_array($notifiable) || $notifiable instanceof \Illuminate\Support\Collection) {
                return $this->sendBulk($notifiable, $module, $data);
            }

            // Handle single recipient
            return $this->sendSingle($notifiable, $module, $data);
        } catch (\Exception $e) {
            Log::error('UnifiedNotificationChannel failed to send notification', [
                'notification_class' => get_class($notification),
                'error' => $e->getMessage(),
                'notifiable_type' => is_object($notifiable) ? get_class($notifiable) : gettype($notifiable)
            ]);

            return false;
        }
    }

    /**
     * Send notification to single recipient
     */
    protected function sendSingle($notifiable, string $module, array $data): bool
    {
        if (!$notifiable || !isset($notifiable->id)) {
            Log::warning('UnifiedNotificationChannel: Invalid notifiable object');
            return false;
        }

        return $this->unifiedService->send(
            module: $module,
            title: $data['title'],
            summary: $data['summary'],
            recipientId: $notifiable->id,
            data: $data['data']
        );
    }

    /**
     * Send notification to multiple recipients
     */
    protected function sendBulk($notifiables, string $module, array $data): bool
    {
        $recipientIds = collect($notifiables)->map(function ($notifiable) {
            return is_object($notifiable) && isset($notifiable->id) ? $notifiable->id : null;
        })->filter()->toArray();

        if (empty($recipientIds)) {
            Log::warning('UnifiedNotificationChannel: No valid recipient IDs found for bulk send');
            return false;
        }

        $result = $this->unifiedService->sendToMultiple(
            module: $module,
            title: $data['title'],
            summary: $data['summary'],
            recipientIds: $recipientIds,
            data: $data['data']
        );

        return !empty($result);
    }

    /**
     * Extract module name from notification class
     */
    protected function extractModule(Notification $notification): string
    {
        $className = get_class($notification);

        // Map known notification classes to modules
        $moduleMapping = [
            'OrderNotification' => 'new-order',
            'OrderUpdatedNotification' => 'update-order',
            'ProductNotification' => 'new-product',
            'OfferNotification' => 'new-offer',
            'ChatMessage' => 'message',
            'ReplySupportMessageNotification' => 'support-message',
            'NewCommentNotification' => 'comments',
            'InvoiceNotification' => 'invoice',
            'VendorInvoiceNotification' => 'invoice',
            'AdminInvoiceNotification' => 'admin-invoice',
            'PayoutStatusNotification' => 'payout-status',
            'ConsultationInvitationNotification' => 'consultation-invitation',
            'ConsultationCancelled' => 'consultation-cancelled',
            'MeetingInvitationNotification' => 'meeting-invitation',
            'NewLikeNotification' => 'new-like',
            'NewReviewNotification' => 'new-review',
            'PushNotification' => 'push-notifications',
            'CampaignNotification' => 'campaign',
            'AppointmentOfferNotification' => 'appointment-offer',
            'OfferStatusChanged' => 'offer-status',
            'PendingOrderReminderNotification' => 'order-reminder',
        ];

        // Extract class name from full namespace
        $shortClassName = class_basename($className);

        // Return mapped module or fallback to general
        return $moduleMapping[$shortClassName] ?? 'general';
    }

    /**
     * Extract notification data using multiple methods
     */
    protected function extractNotificationData(Notification $notification, $notifiable): array
    {
        $data = [
            'title' => 'Notification',
            'summary' => 'You have a new notification',
            'data' => []
        ];

        // Try different extraction methods
        $this->tryToArrayMethod($notification, $notifiable, $data);
        $this->tryToFirebaseMethod($notification, $notifiable, $data);
        $this->tryToMailMethod($notification, $notifiable, $data);
        $this->tryDirectPropertyAccess($notification, $data);

        return $data;
    }

    /**
     * Try to extract data using toArray method
     */
    protected function tryToArrayMethod(Notification $notification, $notifiable, array &$data): void
    {
        try {
            if (method_exists($notification, 'toArray')) {
                $arrayData = $notification->toArray($notifiable);
                if (is_array($arrayData)) {
                    $data['title'] = $arrayData['title'] ?? $data['title'];
                    $data['summary'] = $arrayData['summary'] ?? $arrayData['body'] ?? $data['summary'];
                    $data['data'] = array_merge($data['data'], $arrayData);
                }
            }
        } catch (\Exception $e) {
            // Silently fail and try other methods
        }
    }

    /**
     * Try to extract data using toFirebase method
     */
    protected function tryToFirebaseMethod(Notification $notification, $notifiable, array &$data): void
    {
        try {
            if (method_exists($notification, 'toFirebase')) {
                $firebaseData = $notification->toFirebase($notifiable);
                if (is_array($firebaseData)) {
                    $data['title'] = $firebaseData['title'] ?? $data['title'];
                    $data['summary'] = $firebaseData['body'] ?? $data['summary'];
                }
            }
        } catch (\Exception $e) {
            // Silently fail and try other methods
        }
    }

    /**
     * Try to extract data using toMail method
     */
    protected function tryToMailMethod(Notification $notification, $notifiable, array &$data): void
    {
        try {
            if (method_exists($notification, 'toMail')) {
                $mailData = $notification->toMail($notifiable);
                if (is_object($mailData)) {
                    if (isset($mailData->subject)) {
                        $data['title'] = $mailData->subject;
                    }
                    if (isset($mailData->greeting)) {
                        $data['summary'] = $mailData->greeting;
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail and try other methods
        }
    }

    /**
     * Try to extract data by accessing notification properties directly
     */
    protected function tryDirectPropertyAccess(Notification $notification, array &$data): void
    {
        try {
            $reflection = new ReflectionClass($notification);

            // Try common property names
            $titleProperties = ['title', 'subject', 'heading'];
            $summaryProperties = ['summary', 'body', 'message', 'content'];

            foreach ($titleProperties as $property) {
                if ($reflection->hasProperty($property)) {
                    $prop = $reflection->getProperty($property);
                    if ($prop->isPublic() || $prop->isProtected()) {
                        $prop->setAccessible(true);
                        $value = $prop->getValue($notification);
                        if (!empty($value)) {
                            $data['title'] = $value;
                            break;
                        }
                    }
                }
            }

            foreach ($summaryProperties as $property) {
                if ($reflection->hasProperty($property)) {
                    $prop = $reflection->getProperty($property);
                    if ($prop->isPublic() || $prop->isProtected()) {
                        $prop->setAccessible(true);
                        $value = $prop->getValue($notification);
                        if (!empty($value)) {
                            $data['summary'] = $value;
                            break;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail - we'll use defaults
        }
    }
}
