<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\OTPController as ApiOTPController;
use App\Http\Requests\PhoneRequest;
use App\Http\Requests\VerifyPhoneRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class OTPController extends ApiOTPController
{
    public function verify(Request $request)
    {
        try {
            $res = parent::verify($request);

            return $this->successJsonRes($res);
        } catch (\Throwable $th) {
            return $this->errorJsonRes([
                'phone' => [__('api.phone_verification_failed')],
            ], __('api.phone_verification_failed'), 422);
        }
    }

    public function sendOtp(PhoneRequest $request)
    {
        try {
            if (Setting::checkTestingMode()) {
                return $this->successJsonRes([
                    'is_otp_sent' => true,
                    'otp' => 1111,
                ], __('api.otp_sent'), 200);
            }

            parent::sendOtp($request);

            return $this->successJsonRes([
                'is_otp_sent' => true,
            ], __('api.otp_sent'), 200);
        } catch (\Throwable $th) {
            if ($th instanceof \Illuminate\Validation\ValidationException) {
                throw $th;
            }
            $res = json_decode((string) $th->getResponse()?->getBody());
            if ($th instanceof \GuzzleHttp\Exception\ClientException) {
                if ($th->getCode() == 402) {
                    // 402 Not enough credit.
                } elseif ($th->getCode() == 400) {
                    // 400 Invalid phone number.
                    return $this->errorJsonRes([
                        'phone' => [__('api.invalid_phone')],
                    ], __('api.otp_not_sent'), 422);
                }
            }
            $this->logReq($res?->message);

            return $this->successJsonRes([
                'is_otp_sent' => false,
            ], __('api.contact_support'), 422);
        }
    }

    public function verifyOtp(VerifyPhoneRequest $request)
    {
        if (Setting::checkTestingMode()) {
            return $this->successJsonRes([
                'is_otp_verified' => true,
            ], __('api.correct_otp'), 200);
        }

        $bool = parent::verifyOtp($request);
        if ($bool) {
            return $this->successJsonRes([
                'is_otp_verified' => true,
            ], __('api.correct_otp'), 200);
        } else {
            return $this->errorJsonRes([
                'otp' => [__('api.incorrect_otp')],
            ], __('api.incorrect_otp'), 406);
        }
    }
}
