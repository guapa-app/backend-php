<?php

namespace App\Http\Requests\V3_1\User;

use App\Http\Requests\FailedValidationRequest;
use App\Models\UserProfile;
use App\Rules\ImageOrArray;
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
 */
class UpdateUserRequest extends FailedValidationRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'                  => 'sometimes|required|string|min:3|max:100',
            'email'                 => [
                'nullable',
                'email',
                Rule::unique('users')->ignore(auth()->id())
            ],
            'profile'               => 'sometimes|array',
            'profile.firstname'     => 'sometimes|required|string|max:32',
            'profile.lastname'      => 'sometimes|required|string|max:32',
            'profile.gender'        => 'sometimes|required|in:' . implode(',', UserProfile::GENDER),
            'profile.birth_date'    => 'nullable|date|before:today',
            'profile.about'         => 'nullable|string|min:10|max:1024',
            'profile.photo'         => ['nullable', new ImageOrArray(), 'max:10240'],
            'profile.remove_photo'  => 'nullable|boolean',
            'password'              => 'sometimes|required|string|min:6|confirmed',
            'oldpassword'          => 'required_with:password|string',
        ];

        return $rules;
    }

    public function messages()
    {
        return [];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'profile' => array_merge($this->input('profile', []), [
                'remove_photo' => $this->input('profile.remove_photo', false),
            ]),
        ]);
    }
}
