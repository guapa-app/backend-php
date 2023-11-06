<?php

namespace App\Traits;

use App\Models\Device;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notification;

trait FcmNotifiable
{
    /**
     * Route notifications for the FCM channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @return array
     */
    public function routeNotificationForFcm(Notification $notification): array
    {
        return $this->devices()->pluck('fcmtoken')->toArray();
    }

    /**
     * Get user devices.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function devices(): MorphMany
    {
        return $this->morphMany(Device::class, 'user');
    }
}
