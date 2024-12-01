<?php

namespace App\Http\Requests\V3_1\Vendor;

use App\Helpers\Common;
use App\Http\Requests\FailedValidationRequest;
use App\Models\Address;
use App\Models\Setting;
use App\Models\Vendor;
use App\Rules\ImageOrArray;

class CreateVendorRequest extends FailedValidationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user();
    }

    /**
     * Handle data to be validated from the request.
     * @return array
     */
    public function validationData(): array
    {
        $inputs = parent::validationData();

        $siteKeys = ['twitter', 'instagram', 'snapchat', 'website_url', 'known_url'];

        foreach ($siteKeys as $key) {
            if (array_key_exists($key, $inputs) && !empty($inputs[$key]) && !\Str::startsWith('http', $inputs[$key])) {
                $inputs[$key] = 'https://' . $inputs[$key];
            }
        }

        return $inputs;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $input = $this->all();

        if (isset($input['phone'])) {
            $input['phone'] = Common::removeZeroFromPhoneNumber($input['phone']);
        }

        if (isset($input['whatsapp'])) {
            $input['whatsapp'] = Common::removeZeroFromPhoneNumber($input['whatsapp']);
        }

        $this->replace($input);

        $phoneNumbersRule = Setting::isAllMobileNumsAccepted() ? '' : Common::phoneValidation();

        $rules = [
            'name' => 'required|string|min:5|max:150',
            'about' => 'nullable|string|min:10|max:1024',
            'logo' => ['nullable', new ImageOrArray(), 'max:10240'],
            'type' => 'required|integer|in:' . implode(',', array_keys(Vendor::TYPES)),
            'tax_number' => 'nullable|string|max:200',
            'cat_number' => 'nullable|string|max:200',
            'reg_number' => 'nullable|string|max:200',

            'whatsapp' => 'nullable|' . $phoneNumbersRule,
            'twitter' => 'nullable|string|max:200',
            'instagram' => 'nullable|string|max:200',
            'snapchat' => 'nullable|string|max:200',
            'website_url' => 'nullable|string|max:200',
            'known_url' => 'nullable|string|max:200',
            'health_declaration' => 'nullable|string|max:200',

            'specialty_ids'         => 'sometimes|array|min:1',
            'specialty_ids.*'       => 'integer|exists:taxonomies,id',

            'working_days' => 'nullable|string',
            'working_hours' => 'nullable|string',
            'work_days' => 'sometimes|required|array|min:1',
            'work_days.*' => 'required|integer|min:0|max:6',

            'appointments' => 'sometimes|array|min:1',
            'appointments.*' => 'required|array|min:2',
            'appointments.*.from_time' => 'required|date_format:H:i:s',
            'appointments.*.to_time' => 'required|date_format:H:i:s',

            // Address validation rules
            'address.city_id' => 'required|integer|exists:cities,id',
            'address.address_1' => 'required|string|max:250',
            'address.address_2' => 'nullable|string|max:250',
            'address.postal_code' => 'nullable|string',
            'address.lat' => 'nullable|numeric',
            'address.lng' => 'nullable|numeric',
            'address.type' => 'required|integer|in:' . implode(',', array_keys(Address::TYPES)),
        ];

        return $rules;
    }
}
