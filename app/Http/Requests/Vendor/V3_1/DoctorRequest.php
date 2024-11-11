<?php

namespace App\Http\Requests\Vendor\V3_1;

use App\Helpers\Common;
use App\Http\Requests\FailedValidationRequest;
use App\Models\Setting;
use Illuminate\Contracts\Validation\ValidationRule;

class DoctorRequest extends FailedValidationRequest
{
    public function authorize()
    {
        return $this->user()->vendor->id == request()->vendor;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $input = $this->all();

        // Check and modify the phone number for the vendor
        if (isset($input['phone'])) {
            $input['phone'] = Common::removeZeroFromPhoneNumber($input['phone']);
        }

        $this->replace($input);

        $phoneNumbersRule = Setting::isAllMobileNumsAccepted() ? '' :  '|' .Common::phoneValidation();

        $rules = [
            'name' => 'required|string|min:5|max:150',
            'email' => 'required|email|unique:vendors,email|unique:users,email',
            'phone' => 'required|unique:vendors,phone|unique:users,phone' . $phoneNumbersRule,

            'about' => 'nullable|string|min:10|max:1024',

            'specialty_ids' => 'sometimes|array|min:1',
            'specialty_ids.*' => 'integer|exists:taxonomies,id',

            'logo' => [
                'nullable',
                'string',
                'regex:/^data:image\/(png|jpg|jpeg|gif|svg|webp);base64,/',
            ],
        ];

        return $rules;
    }
}
