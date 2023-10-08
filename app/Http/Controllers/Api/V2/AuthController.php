<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Requests\PhoneRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyPhoneRequest;
use App\Http\Resources\UserResource;
use App\Models\Setting;
use Illuminate\Http\Request;

class AuthController extends ApiAuthController
{
    public function register(RegisterRequest $request)
    {
        list($token, $user) = parent::register($request);
        
        $user->access_token = $token;
        return UserResource::make($user)
            ->additional([
                "success" => true,
                'message' => __('api.success'),
            ]);
    }

    public function login(Request $request)
    {
        list($token, $user) = parent::login($request);
        
        $user->access_token = $token;
        return UserResource::make($user)
            ->additional([
                "success" => true,
                'message' => __('api.success'),
            ]);
    }

    public function user()
    {
        return UserResource::make(parent::user())
            ->additional([
                "success" => true,
                'message' => __('api.success'),
            ]);
    }

    public function refreshToken(Request $request)
    {
        return $this->successJsonRes(parent::refreshToken($request), __('api.success'));
    }

    public function logout(Request $request)
    {
        parent::logout($request);
        return $this->successJsonRes([], __('api.success'));
    }

    public function verify(Request $request)
    {
        return $this->successJsonRes(parent::verify($request));
    }

    public function deleteAccount(Request $request)
    {
        parent::deleteAccount($request);
        return $this->successJsonRes([], __('api.account_deleted'));
    }

    public function sendSinchOtp(PhoneRequest $request)
    {
        try {
            if (Setting::checkTestingMode() || config('app.env') === 'local') {
                return $this->successJsonRes([
                    "is_otp_sent" => true,
                    "otp" => 1111
                ], __('api.otp_sent'), 200);
            }

            parent::sendSinchOtp($request);

            return $this->successJsonRes([
                "is_otp_sent" => true
            ], __('api.otp_sent'), 200);
        } catch (\Throwable $th) {
            if ($th instanceof \Illuminate\Validation\ValidationException) {
                throw $th;
            }
            $res = json_decode((string)$th->getResponse()?->getBody());
            if ($th instanceof \GuzzleHttp\Exception\ClientException) {
                if ($th->getCode() == 402) {
                    // 402 Not enough credit.
                } elseif ($th->getCode() == 400) {
                    // 400 Invalid phone number.
                    return $this->errorJsonRes([
                        'phone' => [__('api.invalid_phone')]
                    ], __('api.otp_not_sent'), 422);
                }
            }
            $this->logReq($res?->message);
            return $this->successJsonRes([
                "is_otp_sent" => false
            ], __('api.contact_support'), 422);
        }
    }

    public function verifySinchOtp(VerifyPhoneRequest $request)
    {
        if (Setting::checkTestingMode() || config('app.env') === 'local') {
            return $this->successJsonRes([
                "is_otp_verified" => true
            ], __('api.correct_otp'), 200);
        }

        $bool = parent::verifySinchOtp($request);
        if ($bool) {
            return $this->successJsonRes([
                "is_otp_verified" => true
            ], __('api.correct_otp'), 200);
        } else {
            return $this->errorJsonRes([
                'otp' => [__('api.incorrect_otp')]
            ], __('api.incorrect_otp'), 406);
        }
    }

    public function checkIfPhoneExist(Request $request)
    {
        $user = parent::checkIfPhoneExist($request);

        if ($user == null) {
            return $this->successJsonRes([
                "is_phone_exists" => false
            ], __('api.phone_doesnt_exist'), 200);
        } else {
            return $this->successJsonRes([
                "is_phone_exists" => true
            ], __('api.phone_exist'), 200);
        }
    }
}
