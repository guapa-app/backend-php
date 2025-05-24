<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class UnifiedNotificationService
{
    protected $externalService;
    protected $channelResolver;

    public function __construct(
        ExternalNotificationService $externalService,
        NotificationChannelResolver $channelResolver
    ) {
        $this->externalService = $externalService;
        $this->channelResolver = $channelResolver;
    }

    /**
     * Send a notification using the configured channel settings
     *
     * @param string $module The notification module/type
     * @param string $title Notification title
     * @param string $summary Notification summary/body
     * @param int $recipientId The recipient user/vendor ID
     * @param array $data Additional notification data
     * @param int|null $adminId The admin ID to use for channel resolution (defaults to current auth user)
     * @return bool Success status
     */
    public function send(
        string $module,
        string $title,
        string $summary,
        int $recipientId,
        array $data = [],
        ?int $adminId = null
    ): bool {
        // Use current authenticated admin if not specified
        if ($adminId === null && Auth::guard('admin')->check()) {
            $adminId = Auth::guard('admin')->id();
        }

        // Resolve the channels to use for this module and admin
        $channels = $this->channelResolver->resolve($module, $adminId);

        // Prepare payload for external service
        $payload = [
            'module' => $module,
            'title' => $title,
            'summary' => $summary,
            'data' => $data,
            'recipient_id' => $recipientId,
            'channels' => $channels,
        ];

        // Send via external service
        return $this->externalService->send($payload);
    }

    /**
     * Send a notification to multiple recipients
     *
     * @param string $module The notification module/type
     * @param string $title Notification title
     * @param string $summary Notification summary/body
     * @param array $recipientIds Array of recipient user/vendor IDs
     * @param array $data Additional notification data
     * @param int|null $adminId The admin ID to use for channel resolution
     * @param bool $useBatch Whether to use batch API (default: true for 5+ recipients)
     * @return array|bool Array of success statuses for each recipient, or bool for batch
     */
    public function sendToMultiple(
        string $module,
        string $title,
        string $summary,
        array $recipientIds,
        array $data = [],
        ?int $adminId = null,
        ?bool $useBatch = null
    ) {
        if (empty($recipientIds)) {
            return [];
        }

        // Use current authenticated admin if not specified
        if ($adminId === null && Auth::guard('admin')->check()) {
            $adminId = Auth::guard('admin')->id();
        }

        // Auto-determine batch usage if not specified
        if ($useBatch === null) {
            $useBatch = count($recipientIds) >= 5;
        }

        // Use batch API for efficiency with many recipients
        if ($useBatch) {
            return $this->sendBatch($module, $title, $summary, $recipientIds, $data, $adminId);
        }

        // Send individually for smaller groups or when batch is disabled
        $results = [];
        foreach ($recipientIds as $recipientId) {
            $results[$recipientId] = $this->send(
                $module,
                $title,
                $summary,
                $recipientId,
                $data,
                $adminId
            );
        }

        return $results;
    }

    /**
     * Send batch notification using external service batch API
     *
     * @param string $module
     * @param string $title
     * @param string $summary
     * @param array $recipientIds
     * @param array $data
     * @param int|null $adminId
     * @return bool
     */
    protected function sendBatch(
        string $module,
        string $title,
        string $summary,
        array $recipientIds,
        array $data = [],
        ?int $adminId = null
    ): bool {
        // Resolve the channels to use for this module and admin
        $channels = $this->channelResolver->resolve($module, $adminId);

        // Prepare batch payload for external service
        $payload = [
            'module' => $module,
            'title' => $title,
            'summary' => $summary,
            'data' => $data,
            'recipient_ids' => $recipientIds,
            'channels' => $channels,
        ];

        // Send via external service batch API
        return $this->externalService->sendBatch($payload);
    }
}
