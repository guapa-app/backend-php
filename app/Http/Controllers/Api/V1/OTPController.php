<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\OTPController as ApiOTPController;
use App\Http\Requests\PhoneRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyPhoneRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class OTPController extends ApiOTPController
{
    public function verify(Request $request)
    {
        return response()->json(parent::verify($request));
    }

    public function sendOtp(PhoneRequest $request)
    {
        if (!Setting::checkTestingMode()) {
            parent::sendOtp($request);
        }

        return response()->json(['message' => __('api.otp_sent')]);
    }

    public function verifyOtp(VerifyPhoneRequest $request)
    {
        $bool = true;

        if (!Setting::checkTestingMode()) {
            $bool = parent::verifyOtp($request);
        }

        if ($bool) {
            return response()->json(['message' => __('api.correct_otp')], 200);
        } else {
            return response()->json(['message' => __('api.incorrect_otp')], 406);
        }
    }
}
