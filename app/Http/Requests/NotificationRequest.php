<?php

namespace App\Http\Requests;

class NotificationRequest extends FailedValidationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type'          => 'required|string|in:user,vendor,android,ios',
            'recipients'    => 'nullable|array',
            'title'         => 'required|string',
            'summary'       => 'required|string',
            'image'         => 'nullable|image',
        ];
    }
}
