<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use GuzzleHttp\Client;

/**
 * @group Notifications
 */
class NotificationController extends BaseApiController
{
    /**
     * Get user notifications
     *
     * Notification types and corresponding data
     * new-product, new-service => product_id
     * new-offer => product_id
     *
     * @responseFile 200 responses/notifications/list.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @queryParam page Page number for pagination Example: 2
     * @queryParam perPage Results to fetch per page Example: 15
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->get('perPage');
        $notifications = $this->user->notifications()->paginate($perPage ?: 15);

        $notifications->getCollection()->transform(function($notification) {
            $data = $notification->data;
            if (isset($data['summary'])) {
                $notification->summary = $data['summary'];
            } else {
                $notification->summary = '';
            }

            return $notification;
        });

    	return response()->json($notifications);
    }

    /**
     * Get only unread notifications
     *
     * @responseFile 200 responses/notifications/list.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @queryParam page Page number for pagination Example: 2
     * @queryParam perPage Results to fetch per page Example: 15
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unread(Request $request)
    {
        $perPage = $request->get('perPage');
        $notifications = $this->user->unreadNotifications()->paginate($perPage ?: 15);

        $notifications->getCollection()->transform(function($notification) {
            $data = $notification->data;
            if (isset($data['summary'])) {
                $notification->summary = $data['summary'];
            } else {
                $notification->summary = '';
            }

            return $notification;
        });

    	return response()->json($notifications);
    }

    /**
     * Get unread notifications count
     *
     * @responseFile 200 responses/notifications/unread_count.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unread_count()
    {
        return response()->json([
            'count' => $this->user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark all as read
     *
     * @responseFile 200 responses/notifications/mark_read.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
    	$now = Carbon::now();
    	$this->user->unreadNotifications()->update(['read_at' => $now]);
    	return response()->json(["message" => __('api.success')], 200);
    }

    /**
     * Mark notification as read
     *
     * @urlParam id required Notification id
     *
     * @responseFile 200 responses/notifications/mark_read.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @param  integer $notifId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markRead($notifId = 0)
    {
    	$now = Carbon::now();
    	$this->user->unreadNotifications()->where('id', $notifId)->update([
            'read_at' => $now,
        ]);

    	return response()->json(["message" => __('api.success')], 200);
    }
}
