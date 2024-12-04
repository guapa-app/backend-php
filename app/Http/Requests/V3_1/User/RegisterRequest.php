<?php

namespace App\Http\Requests\V3_1\User;

use App\Helpers\Common;
use App\Http\Requests\FailedValidationRequest;
use App\Models\Setting;
use App\Models\UserProfile;

/**
 * @bodyParam name string Full name (required if firstname is absent). Example: Mohamed Ahmed
 * @bodyParam phone     string required Phone number with country code. Example: +201065987456
 * @bodyParam email     string Email address. Example: user@example.com
 * @bodyParam password string required Password. Example: 445566332255
 * @bodyParam password_confirmation string required Password confirmation. Example 445566332255
 * @bodyParam referral_code string inviter referral code
 */
class RegisterRequest extends FailedValidationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $input = $this->all();

        // Check and modify the phone number for the user
        if (isset($input['phone'])) {
            $input['phone'] = Common::removeZeroFromPhoneNumber($input['phone']);
        }

        $this->replace($input);

        return [
            'name' => 'required|string|min:3|max:64',
            'email' => 'sometimes|required|email|unique:users,email',
            'phone' => 'required|unique:users,phone|'.(Setting::isAllMobileNumsAccepted() ? '' : Common::phoneValidation()),
            'gender' => 'nullable|string',
            'referral_code' => 'nullable|exists:user_profiles,referral_code',
        ];
    }
}
