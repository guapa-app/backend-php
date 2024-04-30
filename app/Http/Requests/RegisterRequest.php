<?php

namespace App\Http\Requests;

use App\Helpers\Common;
use App\Models\UserProfile;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam name string Full name (required if firstname is absent). Example: Mohamed Ahmed
 * @bodyParam firstname string First name (required if name is absent). Example: Mohamed
 * @bodyParam lastname  string Last name (required if name is absent). Example: Ahmed
 * @bodyParam phone     string required Phone number with country code. Example: +201065987456
 * @bodyParam email     string Email address. Example: user@example.com
 * @bodyParam firebase_jwt_token string JWT token returned from firebase (optional), required to verify phone and use it for login.
 * @bodyParam password string required Password. Example: 445566332255
 * @bodyParam password_confirmation string required Password confirmation. Example 445566332255
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
            'name'                  => 'required_without:firstname|string|min:3|max:64',
            'firstname'             => 'required_without:name|string|min:3|max:32',
            'lastname'              => 'required_without:name|string|min:3|max:32',
            'email'                 => 'sometimes|required|email|unique:users,email',
            'phone'                 => 'required|unique:users,phone|' . Common::phoneValidation(),
            'firebase_jwt_token'    => 'sometimes|required|string',
            'otp'                   => 'sometimes|required|string|max:10',
            'password'              => 'required|confirmed|min:6|max:100',
            'gender'                => 'required|string|in:' . implode(',', UserProfile::GENDER),
        ];
    }
}
