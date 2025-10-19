<?php

namespace App\Http\Requests;

class AddressListRequest extends FailedValidationRequest
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
        return [
            'perPage'           => 'nullable|numeric',
            'page'              => 'nullable|numeric',
            'order'             => 'nullable|string|in:asc,desc',
            'sort'              => 'nullable',
        ];
    }
}
