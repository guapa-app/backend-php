<?php

namespace App\Services\Meetings;

use App\Contracts\Services\MeetingServiceInterface;
use InvalidArgumentException;

class MeetingServiceFactory
{
    /**
     * Create a meeting service instance based on the provider
     *
     * @param string $provider
     * @return MeetingServiceInterface
     */
    public static function create(string $provider): MeetingServiceInterface
    {
        switch (strtolower($provider)) {
            case 'zoom':
                return app(ZoomMeetingService::class);
            // When implemented
            //case 'google_meet':
            //    return app(GoogleMeetService::class);
            default:
                throw new InvalidArgumentException("Unsupported meeting provider: {$provider}");
        }
    }
} 