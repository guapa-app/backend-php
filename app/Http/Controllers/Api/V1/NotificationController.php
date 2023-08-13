<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\NotificationController as ApiNotificationController;
use Illuminate\Http\Request;

class NotificationController extends ApiNotificationController
{
    public function index(Request $request)
    {
        return response()->json(parent::index($request));
    }

    public function unread(Request $request)
    {
        return response()->json(parent::unread($request));
    }

    public function unread_count()
    {
        return $this->successJsonRes(['count' => parent::unread_count()], __('api.success'));
    }

    public function markAllAsRead()
    {
        parent::markAllAsRead();
        return $this->successJsonRes([], __('api.success'));
    }

    public function markRead(string $notification_id = "")
    {
        parent::markRead($notification_id);
        return $this->successJsonRes([], __('api.success'));
    }
}
