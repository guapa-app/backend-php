<?php

namespace App\Http\Requests\V3;

use Illuminate\Foundation\Http\FormRequest;

class VendorSocialMediaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->hasVendor((int) $this->route('vendor')->id) && !$this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $social_medium = $this->route('social_medium');

        $rules = [
            'social_media_id' => ($social_medium ? 'nullable' : 'required') .'|integer|exists:social_media,id',
            'link'            => 'required|string',
        ];

        return $rules;
    }
}
