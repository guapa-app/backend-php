<?php

namespace App\Http\Requests;

/**
 * @bodyParam subject string required Message subject
 * @bodyParam body    string required Message body
 * @bodyParam phone   string required Phone number
 */
class SupportMessageRequest extends FailedValidationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->route('id');

        return !is_numeric($id) || ($this->user() && $this->user()->isAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('id');

        return is_numeric($id) ? [
            'read' => 'required|string',
        ] : [
            'subject' => 'required|string|max:100',
            'body' => 'required|string|max:1000',
        ];
    }
}
