<?php

namespace App\Http\Requests\V3_1\Vendor;

use App\Helpers\Common;
use App\Http\Requests\FailedValidationRequest;
use App\Models\Setting;
use App\Models\UserProfile;
use App\Models\Vendor;

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
            'name'      => 'required|string|min:3|max:64',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'required|unique:users,phone|' . (Setting::isAllMobileNumsAccepted() ? '' : Common::phoneValidation()),
            'type'      => 'required|integer|in:' . implode(',', array_keys(Vendor::TYPES)),
            'gender'    => 'nullable|string|in:' . implode(',', UserProfile::GENDER),
        ];
    }
}
