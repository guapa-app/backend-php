<?php

namespace App\Http\Requests;

use App\Rules\ImageOrArray;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @bodyParam name string Fullname 3 to 100 characters
 * @bodyParam email string Email address
 * @bodyParam profile.gender string Gender Male, Female, Other
 * @bodyParam profile.birth_date date Date of birth yyyy-mm-dd
 * @bodyParam profile.about string Bio
 * @bodyParam profile.photo image Profile picture
 * @bodyParam address.city_id int required City id
 * @bodyParam address.address_1 string required Address line 1
 * @bodyParam address.address_2 string Address line 2
 * @bodyParam address.postal_code string Postal code
 * @bodyParam address.lat string Latitude
 * @bodyParam address.lng string Longitude
 * @bodyParam password string New password required for change password
 * @bodyParam oldpassword string Old password required for change password
 * @bodyParam password_confirmation string Confirm new password
 * @bodyParam reset_token Reset password token in case of password reset instead of oldpassword
 */
class UserRequest extends FailedValidationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->route('id');
        // Only admins can create users using this request
        if (!is_numeric($id) && !$this->user()->isAdmin()) {
            return false;
        }

        // The user can update his own profile only
        if (is_numeric($id) && !$this->user()->isAdmin() && $id != $this->user()->id) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('id');

        $rules = [
            'name'                  => 'required|string|min:3|max:100',
            'email'                 => ['nullable', 'email', Rule::unique('users')],
            'phone'                 => 'required|string|min:4|max:20|unique:users,phone',
            'profile'               => 'sometimes|array',
            'profile.firstname'     => 'sometimes|required|string|max:32',
            'profile.lastname'      => 'sometimes|required|string|max:32',
            'profile.gender'        => 'sometimes|required|in:Male,Female,Other',
            'profile.birth_date'    => 'nullable|date|before:today',
            'profile.about'         => 'nullable|string|min:10|max:1024',
            'profile.photo'         => ['nullable', new ImageOrArray(), 'max:10240'],
            'password'              => 'required|string|min:6|confirmed',
        ];

        if (is_numeric($id)) {
            // Updating user
            $rules = array_merge($rules, [
                'name'              => 'sometimes|required|string|min:3|max:100',
                'email'             => ['nullable', 'email', Rule::unique('users')->ignore($id)],
                'password'          => 'sometimes|required|string|min:6|confirmed',
                'oldpassword'       => 'sometimes|string',
                'reset_token'       => 'sometimes|string',
            ]);

            unset($rules['phone']);
        }

        if ($this->user() && $this->user()->isAdmin()) {
            $rules['status'] = 'required|in:Active,Closed';
        }

        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
