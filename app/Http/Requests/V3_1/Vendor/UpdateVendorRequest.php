<?php

namespace App\Http\Requests\V3_1\Vendor;

use App\Helpers\Common;
use App\Http\Requests\FailedValidationRequest;
use App\Models\Setting;
use App\Models\Vendor;
use App\Rules\ImageOrArray;
use Illuminate\Validation\Rule;

/**
 * @bodyParam type integer required Vendor type (0: hospital, 1: clinic, etc). Example: 0
 * @bodyParam name string required Vendor name. Example: Best Clinic
 * @bodyParam email string required Vendor email. Example: manager@bestclinic.com
 * @bodyParam phone string required Vendor phone. Example: +201023569856
 * @bodyParam about string Vendor about info. Example: The best clinic in town
 * @bodyParam specialty_ids integer[] Vendor specializations.
 * @bodyParam specialty_ids[].* integer Specialty id.
 * @bodyParam logo file Vendor logo.
 * @bodyParam whatsapp string Vendor whatsapp number.
 * @bodyParam twitter string Vendor twitter url.
 * @bodyParam instagram string Vendor instagram url.
 * @bodyParam snapchat string Vendor snapchat url.
 * @bodyParam working_days string Working days.
 * @bodyParam working_hours string Working hours.
 * @bodyParam address object Vendor address.
 * @bodyParam address.city_id integer required City id. Example: 65
 * @bodyParam address.address_1 string required Address line 1. Example: XYZ Street
 * @bodyParam address.address_2 string Address line 2. Example: 6th floor, next to xyz restaurant
 * @bodyParam address.postal_code string Postal code. Example: 56986
 * @bodyParam address.lat number Latitude. Example: 65.236589
 * @bodyParam address.lng number Longitude. Example: 62.659898
 * @bodyParam address.type integer required Address type (see address types returned in api data). Example: 3
 */
class UpdateVendorRequest extends FailedValidationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!$this->user()) {
            return false;
        }
        return true;
    }

    /**
     * Handle data to be validated from the request.
     * @return array
     */
    public function validationData(): array
    {
        $inputs = parent::validationData();

        $siteKeys = ['twitter', 'instagram', 'snapchat', 'website_url', 'known_url']; // 'whatsapp',

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

        // Check and modify the WhatsApp number for the vendor (assuming it's different from the phone number)
        if (isset($input['whatsapp'])) {
            $input['whatsapp'] = Common::removeZeroFromPhoneNumber($input['whatsapp']);
        }

        $this->replace($input);
        $phoneNumbersRule = Setting::isAllMobileNumsAccepted() ? '' : Common::phoneValidation();

        $rules = [
            'name' => 'sometimes|required|string|min:5|max:150',
            'email' => ['sometimes', 'required', 'email', Rule::unique('vendors', 'email')->ignore($this->user()->managerVendorId())],
            'about' => 'nullable|string|min:10|max:1024',

            'specialty_ids' => 'sometimes|array|min:1',
            'specialty_ids.*' => 'integer|exists:taxonomies,id',

            'logo' => ['nullable', new ImageOrArray(), 'max:10240'],
            'remove_logo' => 'sometimes|boolean',

            'whatsapp' => 'nullable|' . (Setting::isAllMobileNumsAccepted() ? '' : Common::phoneValidation()),
            'twitter' => 'nullable|string|max:200',
            'instagram' => 'nullable|string|max:200',
            'snapchat' => 'nullable|string|max:200',
            'website_url' => 'nullable|string|max:200',
            'known_url' => 'nullable|string|max:200',

            'type' => 'sometimes|required|integer|in:' . implode(',', array_keys(Vendor::TYPES)),
            'working_days' => 'nullable|string',
            'working_hours' => 'nullable|string',

            // Work days array validation (optional)
            'work_days' => 'sometimes|required|array|min:1',
            'work_days.*.day' => 'required|integer|min:0|max:7',
            'work_days.*.start_time' => 'required|date_format:H:i:s',
            'work_days.*.end_time' => 'required|date_format:H:i:s',
            'work_days.*.is_off' => 'sometimes|boolean',


            // Appointments array validation (optional)
            'accept_appointment' => 'sometimes|boolean',

            'accept_online_consultation' => 'sometimes|boolean',
            'consultation_fee' => 'sometimes|required_if:accept_online_consultation,1|numeric|min:0',

            'session_duration' => 'sometimes|integer|min:5|max:60',
            'appointments' => 'sometimes|required|array|min:1',
            'appointments.*' => 'required|array|min:2',
            'appointments.*.from_time' => 'required|date_format:H:i:s',
            'appointments.*.to_time' => 'required|date_format:H:i:s',
        ];
        return $rules;
    }


}
