<?php

namespace App\Http\Requests;

use App\Models\Post;
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
        return [
            'category_id'   => 'required|integer|exists:taxonomies,id',
            'title'         => 'required|string|max:200',
            'content'       => 'required',
            'status'        => 'required|in:' . implode(',', array_keys(Post::STATUSES)),
            'media'         => 'sometimes|array|min:1',
            'media.*'       => 'required|image|max:10240',
            'keep_media'    => 'sometimes|array|min:1',
            'keep_media.*'  => 'required|integer|exists:media,id',
            'youtube_url'   => 'nullable|url|max:200',
        ];
    }
}
