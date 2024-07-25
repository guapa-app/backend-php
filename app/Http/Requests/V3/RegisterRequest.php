<?php

namespace App\Http\Requests\V3;

use App\Helpers\Common;
use App\Models\Setting;
use App\Models\UserProfile;
use App\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam name string Full name. Example: Ahmed Abdelkader
 * @bodyParam phone string required Phone number with country code. Example: +201065987456
 * @bodyParam email string Email address. Example: user@example.com
 * @bodyParam OTP string required to verify phone and use it for login.
 * @bodyParam password string sometimes required Password with confirmation. Example: 445566332255
 * @bodyParam gender string. Example Male
 */
class RegisterRequest extends FormRequest
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
            'name'      => 'required|string|min:3|max:64',
            'email'     => 'sometimes|required|email|unique:users,email',
            'phone'     => 'required|unique:users,phone|' . (Setting::isAllMobileNumsAccepted() ? '' : Common::phoneValidation()),
            'otp'       => 'required|string|max:10',
            'gender'    => 'required|string|in:' . implode(',', UserProfile::GENDER),
        ];
    }
}
