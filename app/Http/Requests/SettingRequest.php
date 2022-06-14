<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
        $id = $this->route('id');

        return is_numeric($id) ? [] : [
            'setting_key' => 'required|string|unique:settings,setting_key',
            'setting_value' => 'required|string',
            'setting_unit' => 'nullable|string',
            'instructions' => 'nullable|string',
        ];
    }
}
