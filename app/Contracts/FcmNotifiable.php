<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notification;

interface FcmNotifiable {

	/**
     * Route notifications for the FCM channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * 
     * @return array
     */
    public function routeNotificationForFcm(Notification $notification): array;

    /**
     * Get notifiable model devices
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function devices(): MorphMany;
}
