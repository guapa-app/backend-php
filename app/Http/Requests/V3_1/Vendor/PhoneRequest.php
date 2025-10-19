<?php

namespace App\Http\Requests\V3_1\Vendor;

use App\Http\Requests\FailedValidationRequest;

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
            'phone' => 'required|string|numeric',
        ];
    }
}
