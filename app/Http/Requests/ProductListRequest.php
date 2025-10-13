<?php

namespace App\Http\Requests;

class ProductListRequest extends FailedValidationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type'              => 'nullable|in:product,service',
            'list_type'         => 'nullable|in:default,most_viewed,most_ordered,offers',
            'keyword'           => 'nullable|string',
            'min_price'         => 'nullable|numeric|gt:0',
            'max_price'         => 'nullable|numeric|gt:0',
            'category_ids'      => 'nullable|array',
            'category_ids.*'    => 'numeric|exists:taxonomies,id',
            'vendor_ids'        => 'nullable|array',
            'vendor_id.*'       => 'nullable|integer|exists:vendors,id',
            'city_ids'          => 'nullable|array',
            'city_ids.*'        => 'nullable|numeric|exists:cities,id',
            'min_distance'      => 'nullable|numeric|gt:0',
            'max_distance'      => 'nullable|numeric|gt:0',
            'sort_by'           => 'nullable|in:price',
            'sort_order'        => 'nullable|in:asc,desc',
            'lat'               => 'nullable|numeric|between:-90,90',
            'lng'               => 'nullable|numeric|between:-180,180',
            'distance'          => 'nullable|numeric|gt:0',
            'page'              => 'nullable|numeric|min:1',
            'perPage'           => 'nullable|numeric|min:1',
        ];
    }
}
