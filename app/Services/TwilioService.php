<?php

namespace App\Services;

use Exception;
use Log;
use Twilio\Rest\Client as TwilioClient;

/**
 * Authentication service.
 */
class TwilioService
{
    private $authToken;
    private $serviceId;
    private $verifyServiceId;

    public function __construct()
    {
        $this->authToken = config('twilio.auth_token');
        $this->serviceId = config('twilio.service_id');
        $this->verifyServiceId = config('twilio.verify_service_id');
    }

    public function sendOtp(string $phone)
    {
        try {
            $client = new TwilioClient($this->serviceId, $this->authToken);

            $result = $client->verify->v2->services($this->verifyServiceId)
                ->verifications
                ->create($phone, 'sms');

            Log::info(json_encode($result));

            return true;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            // Report the error or do anything with it
            return false;
        }
    }

    public function verifyOtp(string $phone, string $otp): bool
    {
        try {
            $client = new TwilioClient($this->serviceId, $this->authToken);

            $result = $client->verify->v2->services($this->verifyServiceId)
                ->verificationChecks
                ->create([
                    'code' => $otp,
                    'to' => $phone,
                ]);

            return $result->valid;
        } catch (Exception $e) {
            Log::error($e);

            // Report the error or do anything with it
            return false;
        }
    }
}
