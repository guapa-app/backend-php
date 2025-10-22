<?php

namespace App\Http\Requests\V3_1\User;

use Illuminate\Foundation\Http\FormRequest;

class BkamConsultationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'taxonomy_id' => 'required|integer|exists:taxonomies,id',
            'details' => 'required|string',

            // Dynamic medical history questions
            'medical_history' => 'required|array',
            'medical_history.*.question' => 'required|string',
            'medical_history.*.type' => 'required|in:numeric,boolean,text,choice',
            'medical_history.*.answer' => 'nullable',
            'medical_history.*.options' => 'required_if:medical_history.*.type,choice|array',

            'media_ids' => 'sometimes|array|min:1',
            'media_ids.*' => 'required|integer|exists:media,id',
        ];
    }

    public function messages()
    {
        return [
            'medical_history.required' => 'Medical history is required',
            'medical_history.array' => 'Invalid medical history format',
            'medical_history.*.question.required' => 'Question is required',
            'medical_history.*.question.string' => 'Question must be a string',
        ];
    }
}
