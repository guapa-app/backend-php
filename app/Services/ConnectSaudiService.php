<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\OtpVerificationService;

/**
 * Connect Saudi Service
 */
class ConnectSaudiService
{
    protected $otpVerificationService;
    protected $baseUrl;
    protected $user;
    protected $password;
    protected $senderId;

    public function __construct()
    {
        $this->otpVerificationService = new OtpVerificationService();
        $this->baseUrl = config('services.connectsaudi.api_url');
        $this->user = config('services.connectsaudi.user');
        $this->password = config('services.connectsaudi.password');
        $this->senderId = config('services.connectsaudi.sender_id');
    }

    public function sendOtp(string $phone)
    {
        try {
            $otp = $this->otpVerificationService->generateOtp($phone);
            $response = Http::get($this->baseUrl, [
                'user' => $this->user,
                'pwd' => $this->password,
                'senderid' => $this->senderId,
                'mobileno' => $phone,
                'msgtext' => $otp,
                'priority' => 'High',
                'CountryCode' => 'ALL',
            ]);

            if ($response->successful()) {
                return $response->body();
            }

            throw new \Exception('Failed to send SMS: ' . $response->body());
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function verifyOtp(string $phone, string $otp): bool
    {
        try {
            return $this->otpVerificationService->verifyOtp($phone, $otp);
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
