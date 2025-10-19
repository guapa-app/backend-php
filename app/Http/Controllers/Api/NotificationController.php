<?php

namespace App\Http\Controllers\Api;

use App\Enums\NotificationTypeEnum;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

/**
 * @group Notifications
 */
class NotificationController extends BaseApiController
{
    /**
     * Get user notifications.
     *
     * Notification types and corresponding data
     * new-product, new-service => product_id
     * new-offer => product_id
     *
     * @responseFile 200 responses/notifications/list.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @queryParam page number for pagination Example: 2
     * @queryParam perPage Results to fetch per page Example: 15
     *
     * @param  Request  $request
     *
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $perPage = $request->get('perPage');
        $notifications = $this->user->notifications()->filter($request->type)->paginate($perPage ?: 15);

        $notifications->getCollection()->transform(function ($notification) {
            $data = $notification->data;

            if (isset($data['summary'])) {
                $notification->summary = $data['summary'];
            } else {
                $notification->summary = '';
            }

            return $notification;
        });

        return $notifications;
    }

    /**
     * Get only unread notifications.
     *
     * @responseFile 200 responses/notifications/list.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @queryParam page number for pagination Example: 2
     * @queryParam perPage Results to fetch per page Example: 15
     *
     * @return LengthAwarePaginator
     */
    public function unread(Request $request)
    {
        $perPage = $request->get('perPage');
        $notifications = $this->user->unreadNotifications()->paginate($perPage ?: 15);

        $notifications->getCollection()->transform(function ($notification) {
            $data = $notification->data;
            if (isset($data['summary'])) {
                $notification->summary = $data['summary'];
            } else {
                $notification->summary = '';
            }

            return $notification;
        });

        return $notifications;
    }

    /**
     * Get unread notifications count.
     *
     * @responseFile 200 responses/notifications/unread_count.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @return int
     */
    public function unread_count()
    {
        return $this->user->unreadNotifications()->count();
    }

    /**
     * Mark all as read.
     *
     * @responseFile 200 responses/notifications/mark_read.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @return int
     */
    public function markAllAsRead()
    {
        return $this->user->unreadNotifications()
            ->update(['read_at' => Carbon::now()]);
    }

    /**
     * Mark notification as read.
     *
     * @urlParam id required Notification id
     *
     * @responseFile 200 responses/notifications/mark_read.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @param $notification_id
     *
     * @return int
     */
    public function markRead(string $notification_id = '')
    {
        return $this->user->unreadNotifications()
            ->where('id', $notification_id)
            ->update([
                'read_at' => Carbon::now(),
            ]);
    }

    /**
     * Show notifications types.
     *
     * @responseFile 200 responses/notifications/types.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @return array
     */
    public function types()
    {
        return NotificationTypeEnum::cases();
    }

    public function delete($id)
    {
        $notification = $this->user->notifications()->findOrFail($id);

        return $notification->delete();
    }
}
