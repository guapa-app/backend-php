<?php

namespace App\Http\Requests\V3_1\Common;

use App\Http\Requests\FailedValidationRequest;
use Illuminate\Validation\Rule;

class PayByWalletRequest extends FailedValidationRequest
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
        $rules = [
            'id' => ['required', 'integer'],
            'type' => ['required', 'string', Rule::in(['order', 'campaign', 'appointment'])]
        ];

        return $rules;
    }
}
