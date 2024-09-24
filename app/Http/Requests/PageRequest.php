<?php

namespace App\Http\Requests;

class PageRequest extends FailedValidationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title.en' => 'required|string',
            'title.ar' => 'required|string',
            'content.en' => 'required|string',
            'content.ar' => 'required|string',
            'published' => 'required|in:0,1',
        ];
    }
}
