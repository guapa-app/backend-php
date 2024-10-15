<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Vendor\V3_1\NotificationCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(Request $request)
    {
        $perPage = $request->get('perPage');
        $notifications = $this->user->notifications()->filter($request->type)->paginate($perPage ?: 15);

        $this->transformNotifications($notifications);

        return NotificationCollection::make($notifications)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function unread(Request $request)
    {
        $perPage = $request->get('perPage');
        $notifications = $this->user->unreadNotifications()->paginate($perPage ?: 15);

        $this->transformNotifications($notifications);

        return NotificationCollection::make($notifications)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function unread_count()
    {
        return $this->successJsonRes(['count' => $this->user->unreadNotifications()->count()], __('api.success'));
    }

    public function markAllAsRead()
    {
        $this->user->unreadNotifications()
            ->update(['read_at' => Carbon::now()]);
        return $this->successJsonRes([], __('api.success'));
    }

    public function markRead(string $notification_id = '')
    {
        $this->user->unreadNotifications()
            ->where('id', $notification_id)
            ->update([
                'read_at' => Carbon::now(),
            ]);

        return $this->successJsonRes(['is_read' => true], __('api.success'));
    }

    private function transformNotifications($notifications)
    {
        return $notifications->getCollection()->transform(function ($notification) {
            $data = $notification->data;

            $notification->summary = $data['summary'] ?? '';

            return $notification;
        });
    }
}
