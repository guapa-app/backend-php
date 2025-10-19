<?php

namespace App\Http\Requests;

class PhoneRequest extends FailedValidationRequest
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
        return [
            'phone' => [
                'required',
                'string',
                'numeric',
                function ($attribute, $value, $fail) {
                    $normalizedPhone = ltrim($value, '+'); // remove + is exist
                    $exists = \App\Models\User::where('phone', $value)
                        ->orWhere('phone', "+$normalizedPhone") // search with +
                        ->orWhere('phone', $normalizedPhone) // search without +
                        ->exists();

                    if (!$exists) {
                        $fail(__('Sorry, the mobile number you entered is not registered with us. Please register a new account to benefit from our services.'));
                    }
                },
            ],
        ];
    }
}
