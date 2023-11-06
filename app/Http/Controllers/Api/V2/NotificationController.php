<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\NotificationController as ApiNotificationController;
use App\Http\Resources\NotificationCollection;
use Illuminate\Http\Request;

class NotificationController extends ApiNotificationController
{
    public function index(Request $request)
    {
        return NotificationCollection::make(parent::index($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function unread(Request $request)
    {
        return NotificationCollection::make(parent::unread($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function unread_count()
    {
        return $this->successJsonRes(['count' => parent::unread_count()]);
    }

    public function markAllAsRead()
    {
        parent::markAllAsRead();

        return $this->successJsonRes([], __('api.success'));
    }

    public function markRead(string $notification_id = '')
    {
        parent::markRead($notification_id);

        return $this->successJsonRes([], __('api.success'));
    }
}
