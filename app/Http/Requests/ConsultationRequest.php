<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultationRequest extends FailedValidationRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'vendor_id' => 'required|exists:vendors,id',
            'appointment_date' => 'required|date|after_or_equal::today',
            'appointment_time' => 'required|date_format:H:i',
            'type' => 'required|in:video,audio,chat',

            // Dynamic medical history questions
            'medical_history' => 'required|array',
            'medical_history.*.question' => 'required|string',
            'medical_history.*.type' => 'required|in:numeric,boolean,text,choice',
            'medical_history.*.answer' => 'nullable',
            'medical_history.*.options' => 'required_if:medical_history.*.type,choice|array',

            // Chief complaint directly at top level, not in medical history
            'chief_complaint' => 'required|string|max:500',

            'media' => 'nullable|array|min:1',
            'media.*' => 'nullable|string',
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
