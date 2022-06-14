<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use CodeZero\UniqueTranslation\UniqueTranslationRule;
use App\Rules\ImageOrArray;

class TaxonomyRequest extends FormRequest
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
        $rules = [
            'title.en' => 'required|string|max:150',
            'title.ar' => 'required|string|max:150',
            'description.en' => 'nullable|string|max:500',
            'description.ar' => 'nullable|string|max:500',
            'icon' => ['nullable', new ImageOrArray(), 'max:5120'],
            'font_icon' => 'nullable|string|max:100',
            'type' => 'required|string|in:category,tag,specialty,blog_category',
            'parent_id' => 'nullable|integer|exists:taxonomies,id',
        ];

        // Validating title translations as unique
        $rule = 'unique_translation:taxonomies,title';

        $id = $this->route('id');
        if (is_numeric($id)) {
            $rule .= ',' . $id .',id';
        } else {
            $rule .= ',null,null';
        }

        if ($this->has('type')) {
            $type = $this->get('type');
            $rule .= ',type,' . $type;
        }

        $rules['title.*'] = $rule;

        return $rules;
    }
}
