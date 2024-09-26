<?php

namespace App\Http\Requests\V3;

use App\Http\Requests\FailedValidationRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class InfluencerRequest extends FailedValidationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasVendor((int) $this->route('vendor')->id) && !$this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject' => 'required|string|max:100',
            'body' => 'required|string|max:1000',
        ];
    }
}
