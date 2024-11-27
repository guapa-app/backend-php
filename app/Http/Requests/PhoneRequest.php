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
            'phone' => 'required|string|numeric|exists:users,phone',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.exists' => __('Sorry, the mobile number you entered is not registered with us. Please register a new account to benefit from our services.'),
        ];
    }
}
