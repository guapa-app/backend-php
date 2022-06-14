<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
        $statuses = implode(',', array_keys(\App\Models\Post::STATUSES));

        return [
            'category_id' => 'required|integer|exists:taxonomies,id',
            'title' => 'required|string|max:200',
            'content' => 'required',
            'status' => 'required|in:' . $statuses,
            'media' => 'sometimes|array|min:1',
            'media.*' => 'required|image|max:10240',
            'keep_media' => 'sometimes|array|min:1',
            'keep_media.*' => 'required|integer|exists:media,id',
            'youtube_url' => 'nullable|url|max:200',
        ];
    }
}
