<?php

namespace App\Http\Requests;

use App\Helpers\Common;
use App\Models\Setting;

class VendorClientRequest extends FailedValidationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $input = $this->all();

        // Check and modify the phone number for the vendor
        if (isset($input['phone'])) {
            $input['phone'] = Common::removePlusFromPhoneNumber($input['phone']);
        }

        $this->replace($input);

        $phoneNumbersRule = Setting::isAllMobileNumsAccepted() ? '' :  '|' .Common::phoneValidation();

        return [
            'name'      => 'required|string|min:3|max:100',
            'phone'     => 'required|string|' . $phoneNumbersRule,

        ];
    }
}
