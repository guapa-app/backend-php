<?php

namespace App\Http\Controllers\Api;

use App\Services\ExternalNotificationService;
use App\Services\NotificationChannelResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @group Notifications
 */
class NotificationController extends BaseApiController
{
    protected $externalService;
    protected $channelResolver;

    public function __construct(ExternalNotificationService $externalService, NotificationChannelResolver $channelResolver)
    {
        parent::__construct();
        $this->externalService = $externalService;
        $this->channelResolver = $channelResolver;
    }

    // Send notification API
    public function send(Request $request)
    {
        $request->validate([
            'module' => 'required|string',
            'title' => 'required|string',
            'summary' => 'required|string',
            'data' => 'nullable|array',
            'recipient_id' => 'required|integer',
        ]);

        $adminId = Auth::id();
        $channels = $this->channelResolver->resolve($request->module, $adminId);

        $payload = [
            'module' => $request->module,
            'title' => $request->title,
            'summary' => $request->summary,
            'data' => $request->data ?? [],
            'recipient_id' => $request->recipient_id,
            'channels' => $channels,
        ];

        $success = $this->externalService->send($payload);

        if ($success) {
            return response()->json(['success' => true, 'message' => 'Notification sent.']);
        }
        return response()->json(['success' => false, 'message' => 'Failed to send notification.'], 500);
    }
}
