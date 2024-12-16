<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\Log;

class OtpVerificationService
{
    public function generateOtp(string $phoneNumber): string
    {
        // Check OTP request limit
        $requestCount = OtpVerification::where('phone_number', $phoneNumber)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->count();

        if ($requestCount >= 3) {
            Log::warning("OTP request limit reached for phone number: $phoneNumber");
            throw new \Exception("OTP request limit reached for phone number: $phoneNumber");
        }

        $otpCode = rand(1000, 9999);

        // Save OTP to the database
        OtpVerification::create([
            'phone_number' => $phoneNumber,
            'otp' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        return $otpCode;
    }

    public function verifyOtp(string $phoneNumber, string $otpCode): bool
    {
        $otp = OtpVerification::where('phone_number', $phoneNumber)
            ->where('otp', $otpCode)
            ->first();

        if (!$otp || $otp->isExpired()) {
            return false;
        }

        $otp->delete();

        return true;
    }
}
