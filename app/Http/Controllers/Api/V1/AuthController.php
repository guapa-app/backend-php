<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Requests\PhoneRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyPhoneRequest;
use Illuminate\Http\Request;

class AuthController extends ApiAuthController
{
    public function register(RegisterRequest $request)
    {
        list($token, $user) = parent::register($request);

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {
        list($token, $user) = parent::login($request);

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function user()
    {
        return response()->json(parent::user());
    }

    public function refreshToken(Request $request)
    {
        return response()->json(parent::refreshToken($request));
    }

    public function logout(Request $request)
    {
        parent::logout($request);

        return response()->json(['message' => __('api.success')]);
    }

    public function verify(Request $request)
    {
        return response()->json(parent::verify($request));
    }

    public function deleteAccount(Request $request)
    {
        parent::deleteAccount($request);

        return response()->json(['message' => __('api.account_deleted')]);
    }

    public function sendSinchOtp(PhoneRequest $request)
    {
        parent::sendSinchOtp($request);

        return response()->json(['message' => __('api.otp_sent')]);
    }

    public function verifySinchOtp(VerifyPhoneRequest $request)
    {
        $bool = parent::verifySinchOtp($request);
        if ($bool) {
            return response()->json([
                'message' => __('api.correct_otp'),
            ], 200);
        } else {
            return response()->json([
                'message' => __('api.incorrect_otp'),
            ], 406);
        }
    }

    public function checkIfPhoneExist(Request $request)
    {
        $user = parent::checkIfPhoneExist($request);

        if ($user == null) {
            return response()->json([
                'message' => __('api.phone_doesnt_exist'),
            ], 422);
        } else {
            return response()->json([
                'message' => __('api.phone_exist'),
            ], 200);
        }
    }
}
