<?php

namespace App\Http\Requests\Vendor\V3_1;

use App\Helpers\Common;
use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
{
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
            $input['phone'] = Common::removeZeroFromPhoneNumber($input['phone']);
        }

        $this->replace($input);

        $phoneNumbersRule = Setting::isAllMobileNumsAccepted() ? '' : Common::phoneValidation();

        $rules = [
            'name'                  => 'required|string|min:5|max:150',
            'email'                 => 'required|email|unique:vendors,email,unique:users,email',
            'phone'                 => 'required|unique:vendors,phone,unique:users,phone' . $phoneNumbersRule,
            'about'                 => 'nullable|string|min:10|max:1024',

            'specialty_ids'         => 'sometimes|array|min:1',
            'specialty_ids.*'       => 'integer|exists:taxonomies,id',

            'logo'                  => [
                'nullable',
                'string',
                'regex:/^data:image\/(png|jpg|jpeg|gif|svg|webp);base64,/',
            ],
        ];

        return $rules;
    }
}
