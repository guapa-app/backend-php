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
            'category_id'     => 'required|integer|exists:taxonomies,id',
            'product_id'      => 'sometimes|integer|exists:products,id',
            'type'            => 'required|string|in:' . implode(',', PostType::availableForCreateByUser()),
            'content'         => 'required',
            'stars'           => 'integer|min:1|max:5|required_if:type,' . PostType::Review->value,
            'show_user'       => 'sometimes|boolean',
            'service_date'    => 'sometimes|date',

            // votes options required for post type vote
            'vote_options'   => 'required_if:type,' . PostType::Vote->value . '|array|min:2|max:10',
            'vote_options.*' => 'required|string|max:255',

            'media'           => 'sometimes|array|min:1',
            'media.*'         => ['required', new ImageOrBase64(), 'max:10240'],
            'before_images'   => 'sometimes|array|min:1',
            'before_images.*' => ['required', new ImageOrBase64(), 'max:10240'],
            'after_images'    => 'sometimes|array|min:1',
            'after_images.*'  => ['required', new ImageOrBase64(), 'max:10240'],
            'keep_media'      => 'sometimes|array|min:1',
            'keep_media.*'    => 'required|integer|exists:media,id',
        ];
    }
}
