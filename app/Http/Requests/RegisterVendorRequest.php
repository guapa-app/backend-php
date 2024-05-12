<?php

namespace App\Http\Requests;

use App\Helpers\Common;
use App\Models\Address;
use App\Models\Setting;
use App\Models\Vendor;
use App\Rules\ImageOrArray;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class RegisterVendorRequest extends FormRequest
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
        if (isset($input['user']['phone'])) {
            $input['user']['phone'] = Common::removeZeroFromPhoneNumber($input['user']['phone']);
        }

        // Check and modify the phone number for the vendor
        if (isset($input['vendor']['phone'])) {
            $input['vendor']['phone'] = Common::removeZeroFromPhoneNumber($input['vendor']['phone']);
        }

        // Check and modify the WhatsApp number for the vendor (assuming it's different from the phone number)
        if (isset($input['vendor']['whatsapp'])) {
            $input['vendor']['whatsapp'] = Common::removeZeroFromPhoneNumber($input['vendor']['whatsapp']);
        }

        $this->replace($input);

        $phoneNumbersRule = Setting::isAllMobileNumsAccepted() ? '' : Common::phoneValidation();

        $user_rules = [
            'user'                          => 'required|array',
                'user.firstname'                => 'required|string|min:3|max:32',
                'user.lastname'                 => 'required|string|min:3|max:32',
                'user.email'                    => 'required|email|unique:users,email',
                'user.phone'                    => 'required|unique:users,phone|' . $phoneNumbersRule,
                'user.password'                 => 'required|confirmed|min:8|max:100',
        ];

        $vendor_rules = [
            'vendor'                        => 'required|array',
                'vendor.name'                   => 'required|string|min:5|max:150',
                'vendor.type'                   => 'required|integer|in:' . implode(',', array_keys(Vendor::TYPES)),
                'vendor.email'                  => 'required|email|unique:vendors,email',
                'vendor.phone'                  => 'required|' . $phoneNumbersRule,
                'vendor.about'                  => 'nullable|string|min:10|max:1024',

                'vendor.tax_number'             => 'nullable|string|max:200',
                'vendor.cat_number'             => 'nullable|string|max:200',
                'vendor.reg_number'             => 'nullable|string|max:200',
                'vendor.logo'                   => ['nullable', new ImageOrArray(), 'max:10240'],

                'vendor.whatsapp'               => 'nullable|' . $phoneNumbersRule,
                'vendor.twitter'                => 'nullable|string|max:200',
                'vendor.instagram'              => 'nullable|string|max:200',
                'vendor.snapchat'               => 'nullable|string|max:200',
                'vendor.website_url'            => 'nullable|string|max:200',
                'vendor.known_url'              => 'nullable|string|max:200',

            'vendor.address'                       => 'required|array',
                'vendor.address.city_id'               => 'required|integer|exists:cities,id',
                'vendor.address.type'                  => 'required|integer|in:' . implode(',', array_keys(Address::TYPES)),
                'vendor.address.address_1'             => 'required|string|max:250',
                'vendor.address.address_2'             => 'nullable|string|max:250',
        ];

        return $user_rules + $vendor_rules;
    }
}
