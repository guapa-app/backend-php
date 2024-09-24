<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Vendor\V3_1\PhoneRequest;
use App\Models\Setting;
use App\Services\SMSService;

class OTPController extends BaseApiController
{
    private $smsService;

    public function __construct(SMSService $smsService,)
    {
        parent::__construct();
        $this->smsService = $smsService;
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

            $this->smsService->sendOtp($request->phone);

            return $this->successJsonRes([
                'is_otp_sent' => true,
            ], __('api.otp_sent'), 200);
        } catch (\Throwable $th) {
            if ($th instanceof \Illuminate\Validation\ValidationException) {
                throw $th;
            }
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
            $this->logReq(json_decode($th));

            return $this->successJsonRes([
                'is_otp_sent' => false,
            ], __('api.contact_support'), 422);
        }
    }

}
