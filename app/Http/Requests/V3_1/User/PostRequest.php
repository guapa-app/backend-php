<?php

namespace App\Http\Requests\V3_1\User;

use App\Enums\PostType;
use App\Http\Requests\FailedValidationRequest;
use App\Models\Post;
use App\Rules\ImageOrBase64;
use Illuminate\Validation\Rules\Enum;

class PostRequest extends FailedValidationRequest
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
        return [
            'type'            => 'required|string|in:' . implode(',', PostType::availableForCreateByUser()),
            'category_id'     => 'required|integer|exists:taxonomies,id',
            'vendor_id'      => 'sometimes|integer|exists:vendors,id',
            'vendor_name'     => 'string|required_if:type,' . PostType::Review->value,
            'content'         => 'required',
            'stars'           => 'integer|min:1|max:5|required_if:type,' . PostType::Review->value,
            'show_user'       => 'sometimes|boolean',
            'service_date'    => 'sometimes|date',

            // votes options required for post type vote
            'vote_options'   => 'required_if:type,' . PostType::Vote->value . '|array|min:2|max:10',
            'vote_options.*' => 'required|string|max:255',

            'media_ids' => 'sometimes|array|min:1',
            'media_ids.*' => 'required|integer|exists:media,id',
            'before_media_ids' => 'sometimes|array|min:1',
            'before_media_ids.*' => 'required|integer|exists:media,id',
            'after_media_ids' => 'sometimes|array|min:1',
            'after_media_ids.*' => 'required|integer|exists:media,id',
            'keep_media'      => 'sometimes|array|min:1',
            'keep_media.*'    => 'required|integer|exists:media,id',
        ];
    }
}
