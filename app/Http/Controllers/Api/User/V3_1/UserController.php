<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Requests\ChangePhoneRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\User\V3_1\UserResource;
use Illuminate\Http\Request;

class UserController extends ApiUserController
{
    public function single(Request $request, $id)
    {
        return UserResource::make(parent::single($request, $id))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function update(UserRequest $request, $id = 0)
    {
        return UserResource::make(parent::update($request, $id))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
    public function updatePhone(ChangePhoneRequest $request)
    {
        try {
            parent::updatePhone($request);

            return $this->successJsonRes(['is_otp_sent' => true], __('api.otp_sent'), 200);
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
