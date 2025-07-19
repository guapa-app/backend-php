<?php

namespace App\Http\Requests;

use App\Helpers\Common;
use App\Models\Address;
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
class VendorRequest extends FailedValidationRequest
{
    private $id;

    protected function prepareForValidation()
    {
        $this->id = $this->route('id');

        if (str_contains($this->url(), 'v3') && !is_numeric($this->id)) {
            $this->merge([
                // Transforming the name to have the first letter of each word capitalized
                'email' => $this->user()->email,
                'phone' => $this->user()->phone,
            ]);
        }
    }

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

        $action = is_numeric($this->id) ? 'update' : 'create';
        $target = $action === 'update' ? Vendor::findOrFail($this->id) : Vendor::class;

        return $this->user()->can($action, $target);
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

        // Check and modify the phone number for the vendor
        if (isset($input['phone'])) {
            $input['phone'] = Common::removeZeroFromPhoneNumber($input['phone']);
        }

        // Check and modify the WhatsApp number for the vendor (assuming it's different from the phone number)
        if (isset($input['whatsapp'])) {
            $input['whatsapp'] = Common::removeZeroFromPhoneNumber($input['whatsapp']);
        }

        $this->replace($input);

        $phoneNumbersRule = Setting::isAllMobileNumsAccepted() ? '' : Common::phoneValidation();

        $rules = [
            'name'                  => 'required|string|min:5|max:150',
            'email'                 => 'required|email|unique:vendors,email',
            'phone'                 => 'required|' . $phoneNumbersRule,
            'about'                 => 'required|string|min:10|max:1024',

            'specialty_ids'         => 'sometimes|array|min:1',
            'specialty_ids.*'       => 'integer|exists:taxonomies,id',

            'logo'                  => ['nullable', new ImageOrArray(), 'max:10240'],

            'whatsapp'              => 'nullable|' . $phoneNumbersRule,
            'twitter'               => 'nullable|string|max:200',
            'instagram'             => 'nullable|string|max:200',
            'snapchat'              => 'nullable|string|max:200',
            'website_url'           => 'nullable|string|max:200',
            'known_url'             => 'nullable|string|max:200',

            'tax_number'            => 'nullable|string|max:200',
            'cat_number'            => 'nullable|string|max:200',
            'reg_number'            => 'nullable|string|max:200',
            'health_declaration'    => 'nullable|string|max:200',

            'type'                  => 'required|integer|in:' . implode(',', array_keys(Vendor::TYPES)),
            'working_days'          => 'nullable|string',
            'working_hours'         => 'nullable|string',
            'work_days'             => 'sometimes|required|array|min:1',
            'work_days.*'           => 'required|integer|min:0|max:6',

            'appointments'             => 'sometimes|array|min:1',
            'appointments.*'           => 'required|array|min:2',
            'appointments.*.from_time' => 'required|date_format:H:i:s',
            'appointments.*.to_time'   => 'required|date_format:H:i:s',
        ];

        if ($this->user() && $this->user()->isAdmin()) {
            // We are currently using staff key in admin only
            $rules = array_merge($rules, [
                'staff' => 'sometimes|array|min:1',
                'staff.*.user_id' => 'required|integer|exists:users,id',
                'staff.*.role' => 'required|string|in:manager,doctor,staff',
                'staff.*.email' => 'nullable|email',
            ]);

            $rules['verified'] = 'required|boolean';
            $rules['status'] = 'required|in:0,1';
        }

        if (is_numeric($this->id)) {
            // Updating vendor
            $rules = array_merge($rules, [
                'name' => 'sometimes|required|string|min:5|max:150',
                'email' => [
                    'sometimes', 'required', 'email', Rule::unique('vendors')->ignore($this->id),
                ],
                'phone' => 'sometimes|required|string|min:6|max:30',
            ]);

            $rules['type'] = 'sometimes|' . $rules['type'];
        } else {
            // Validate address
            $types = implode(',', array_keys(Address::TYPES));
            $rules = array_merge($rules, [
                'address.city_id' => 'required|integer|exists:cities,id',
                'address.address_1' => 'required|string|max:250',
                'address.address_2' => 'nullable|string|max:250',
                'address.postal_code' => 'nullable|string',
                'address.lat' => 'nullable|numeric',
                'address.lng' => 'nullable|numeric',
                'address.type' => 'required|integer|in:' . $types,
            ]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'staff.*.user_id.exists' => 'The selected staff user doesn\'t exist',
            'staff.*.user_id.integer' => 'The staff user id field must be an integer',
            'staff.*.user_id.required' => 'Please select staff user account',
        ];
    }
}
