<?php

namespace App\Services;

use App\Models\Setting;

class SMSService
{
    private $smsService;

    public function __construct()
    {
        $sms_option = Setting::getSmsService();

        switch ($sms_option) {
            case 'sinch':
                $this->smsService = new SinchService;
                break;
            case 'twilio':
            default:
                $this->smsService = new TwilioService;
                break;
        }
    }

    public function sendOtp(string $phone)
    {
        return $this->smsService->sendOtp($this->preparePhoneNumber($phone));
    }

    public function verifyOtp(string $phone, string $otp): bool
    {
        return $this->smsService->verifyOtp(trim($this->preparePhoneNumber($phone)), trim($otp));
    }

    protected function preparePhoneNumber(string $phone): string
    {
        return preg_replace('/^(?!\+)/', '+', $phone);
    }
}
