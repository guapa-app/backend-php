<?php

namespace App\Http\Requests\Vendor\V3_1;

use App\Helpers\Common;
use App\Http\Requests\FailedValidationRequest;
use App\Models\Setting;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateDoctorRequest extends FailedValidationRequest
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

        $phoneNumbersRule = Setting::isAllMobileNumsAccepted() ? '' : Common::phoneValidation();

        $rules = [
            'name' => 'nullable|string|min:5|max:150',
            'email' => 'nullable|email|unique:vendors,email,' . $this->doctor,
            'phone' => 'nullable|unique:vendors,phone,' . $this->doctor . 'phone' . $phoneNumbersRule,
            'about' => 'nullable|string|min:10|max:1024',
            'status' => 'nullable|string|in:active,closed',

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
