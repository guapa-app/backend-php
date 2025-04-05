<?php

namespace App\Http\Requests;

use App\Helpers\Common;
use App\Models\Setting;
use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
        App::setLocale('ar');

        $input = $this->all();

        // Check and modify the phone number for the user
        if (isset($input['phone'])) {
            $input['phone'] = Common::removeZeroFromPhoneNumber($input['phone']);
        }

        $this->replace($input);

        $phoneNumbersRule = Setting::isAllMobileNumsAccepted() ? '' : Common::phoneValidation();

        $user_rules = [
            'firstname'                => 'required|string|min:3|max:32',
            'lastname'                 => 'required|string|min:3|max:32',
            'email'                    => 'required|email|unique:users,email',
            'phone'                    => 'required|unique:users,phone|' . $phoneNumbersRule,
            'terms'                    => 'required',
        ];

        return $user_rules;
    }
}
