<?php

namespace App\Http\Requests;

use App\Rules\ImageOrArray;
use Illuminate\Foundation\Http\FormRequest;

class HistoryRequest extends FormRequest
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
        $rules = [
            'details' => 'required|max:1000',
            'record_date' => 'sometimes|date|before_or_equal:today',
            'image' => ['nullable', new ImageOrArray(), 'max:10240'],
        ];

        if ($this->user() && $this->user()->isAdmin()) {
            $rules['user_id'] = 'required|integer|exists:users,id';
        }

        return $rules;
    }
}
