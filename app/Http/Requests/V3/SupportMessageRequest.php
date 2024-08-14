<?php

namespace App\Http\Requests\V3;

use App\Http\Requests\SupportMessageRequest as SupportMessageRequestAlias;

/**
 * @bodyParam subject string required Message subject
 * @bodyParam body    string required Message body
 * @bodyParam phone   string required Phone number
 */
class SupportMessageRequest extends SupportMessageRequestAlias
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return parent::rules() +
            [
                'type' => 'required',
            ];
    }
}
