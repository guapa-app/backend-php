<?php

namespace App\Contracts\Services;

interface MeetingServiceInterface
{
    /**
     * Create a virtual meeting
     *
     * @param array $data Meeting information
     * @return array Meeting details including join URL and meeting ID
     */
    public function createMeeting(array $data): array;

    /**
     * Update an existing meeting
     *
     * @param string $meetingId
     * @param array $data Updated meeting information
     * @return array Updated meeting details
     */
    public function updateMeeting(string $meetingId, array $data): array;

    /**
     * Delete a meeting
     *
     * @param string $meetingId
     * @return bool Success status
     */
    public function deleteMeeting(string $meetingId): bool;
}
