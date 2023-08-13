<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class AuthController extends ApiAuthController
{
    public function register(RegisterRequest $request)
    {
        list($token, $user) = parent::register($request);

        return UserResource::make($user)
            ->additional([
                "token" => $token,
                "success" => true,
                'message' => __('api.success'),
            ]);
    }

    public function login(Request $request)
    {
        list($token, $user) = parent::login($request);

        return UserResource::make($user)
            ->additional([
                "token" => $token,
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

    public function sendSinchOtp(Request $request)
    {
        parent::sendSinchOtp($request);
        return $this->successJsonRes([], __('api.otp_sent'));
    }

    public function verifySinchOtp(Request $request)
    {
        $bool = parent::verifySinchOtp($request);
        if ($bool) {
            return $this->successJsonRes([], __('api.correct_otp'), 200);
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
            return $this->errorJsonRes([
                'phone' => [
                    __('api.phone_doesnt_exist')
                ]
            ], __('api.phone_doesnt_exist'), 422);
        } else {
            return $this->successJsonRes([], __('api.phone_exist'), 200);
        }
    }
}
