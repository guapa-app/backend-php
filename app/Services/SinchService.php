<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Log;

class SinchService
{
    private $otpUrl;
    private $userName;
    private $userPassword;

    public function __construct()
    {
        $this->otpUrl = config('sinch.base_url');
        $this->userName = config('sinch.username');
        $this->userPassword = config('sinch.password');
    }

    public function sendOtp(string $phone)
    {
        $http = new Client;

        $response = $http->post($this->otpUrl, [
            'body' => json_encode([
                'identity' => [
                    'type' => 'number',
                    'endpoint' => $phone,
                ],
                'method' => 'sms',
            ]),
            'headers' => $this->getHeaders(),
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    public function verifyOtp(string $phone, string $otp): bool
    {
        $http = new Client;

        try {
            $response = $http->put($this->otpUrl . '/number/' . $phone, [
                'body' => json_encode([
                    'sms' => [
                        'code' => $otp,
                    ],
                    'method' => 'sms',
                ]),
                'headers' => $this->getHeaders(),
            ]);

            $result = json_decode((string) $response->getBody(), true);
            Log::error(json_encode($result));

            return is_array($result) && isset($result['status']) && $result['status'] === 'SUCCESSFUL';
        } catch (Exception $e) {
            // Report the error or do anything with it
            return false;
        }
    }

    private function getHeaders()
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($this->userName . ':' . $this->userPassword),
        ];
    }
}
