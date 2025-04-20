<?php

namespace App\Services\Meetings;

use App\Contracts\Services\MeetingServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use GuzzleHttp\Client;

class ZoomMeetingService implements MeetingServiceInterface
{
    protected string $accessToken;
    protected $client;
    protected $account_id;
    protected $client_id;
    protected $client_secret;

    public function __construct()
    {
        $this->client_id = config('services.zoom.key');
        $this->client_secret = config('services.zoom.secret');
        $this->account_id = config('services.zoom.user_id');

        $this->accessToken = $this->getAccessToken();

        $this->client = new Client([
            'base_uri' => 'https://api.zoom.us/v2/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Generate JWT token for Zoom API authentication
     *
     * @return string
     */
    protected function getAccessToken()
    {
        $client = new Client([
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->client_id . ':' . $this->client_secret),
                'Host' => 'zoom.us',
            ],
        ]);

        $response = $client->request('POST', "https://zoom.us/oauth/token", [
            'form_params' => [
                'grant_type' => 'account_credentials',
                'account_id' => $this->account_id,
            ],
        ]);

        $responseBody = json_decode($response->getBody(), true);
        return $responseBody['access_token'];
    }

    /**
     * Create a Zoom meeting
     *
     * @param array $data Meeting information
     * @return array Meeting details including join URL and meeting ID
     */

    public function createMeeting(array $data): array
    {
        $startTime = Carbon::parse($data['date'] . ' ' . $data['time']);
        $endTime = $startTime->copy()->addMinutes($data['duration'] ?? 60);
        try {
            $response = $this->client->request('POST', 'users/me/meetings', [
                'json' => [
                    'topic' => $data['topic'] ?? 'Online Consultation',
                    "type"          => 2, // 1 => instant, 2 => scheduled, 3 => recurring with no fixed time, 8 => recurring with fixed time
                    'start_time' => $startTime->format('Y-m-d\TH:i:s'),
                    'duration' => $data['duration'] ?? 60,
                    'timezone' => config('app.timezone'),
                    'agenda' => $data['agenda'] ?? 'Medical consultation',
                    "password" => $this->generatePassword(),
                    'settings' => [
                        'host_video' => true,
                        'participant_video' => true,
                        'join_before_host' => true,
                        'mute_upon_entry' => true,
                        'waiting_room' => true,
                        'auto_recording' => 'none',

                    ],
                ],
            ]);
            $res = json_decode($response->getBody(), true);
            $meetingData = $res;
            return [
                'status' => true,
                'provider' => 'zoom',
                'meeting_id' => $meetingData['id'],
                'host_url' => $meetingData['start_url'],
                'join_url' => $meetingData['join_url'],
                'password' => $meetingData['password'] ?? null,
                'data' => $meetingData
            ];

        } catch (\Throwable $th) {
            Log::error('Zoom API error: ' . $th->getMessage());
            return [
                'status' => false,
                'message' => $th->getMessage(),
            ];
        }
    }

    /**
     * Update a Zoom meeting
     *
     * @param string $meetingId
     * @param array $meetingDetails
     * @return array
     */
    public function updateMeeting(string $meetingId, array $meetingDetails): array
    {
        $token = $this->getAccessToken();

        try {
            $response = $this->client->patch("meetings/{$meetingId}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'topic' => $meetingDetails['topic'] ?? 'Medical Consultation',
                    'start_time' => $meetingDetails['start_time'] ?? null,
                    'duration' => $meetingDetails['duration'] ?? null,
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'provider' => 'zoom',
                'meeting_id' => $responseData['id'],
                'join_url' => $responseData['join_url'],
                'start_url' => $responseData['start_url'],
            ];
        } catch (\Exception $e) {
            Log::error('Zoom meeting update failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to update Zoom meeting: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a Zoom meeting
     *
     * @param string $meetingId
     * @return bool
     */
    public function deleteMeeting(string $meetingId): bool
    {
        $token = $this->getAccessToken();

        try {
            $this->client->delete("meetings/{$meetingId}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Zoom meeting deletion failed: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Generate a random password for the meeting
     *
     * @return string
     */
    protected function generatePassword(): string
    {
        return substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789'), 0, 10);
    }
}
