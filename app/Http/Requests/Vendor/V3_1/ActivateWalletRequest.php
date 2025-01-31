<?php

namespace App\Http\Requests\Vendor\V3_1;

use App\Http\Requests\FailedValidationRequest;

class ActivateWalletRequest extends FailedValidationRequest
{
    public function authorize()
    {
        return $this->user();
    }

    public function rules(): array
    {
        $rules = [
            'iban' => 'nullable|string',
            'activate_wallet' => 'required|boolean',
        ];

        return $rules;
    }
}
