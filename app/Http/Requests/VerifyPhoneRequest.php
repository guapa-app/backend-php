<?php

namespace App\Http\Requests;

class VerifyPhoneRequest extends FailedValidationRequest
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
            'phone' => 'required|string|numeric',
            'otp'   => 'required|string|max:10',
        ];
    }
}
