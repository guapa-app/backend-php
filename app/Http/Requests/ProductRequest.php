<?php

namespace App\Http\Requests;

use App\Models\Product;

/**
 * @bodyParam category_id int required Category id for this product.
 * @bodyParam title string required Product title 191 characters max.
 * @bodyParam description string Product description 2000 characters max.
 * @bodyParam price string required Product price 100000000 max.
 * @bodyParam status string required `Published`, `Draft`.
 * @bodyParam media[] required array An array of product images.
 * @bodyParam media[].* image required Product image 10MB max.
 *
 * @bodyParam keep_media array required Array of media ids to keep (Update only).
 * @bodyParam keep_media.* int required Media id returned from server.
 */
class ProductRequest extends FailedValidationRequest
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
     * Handle data to be validated from the request.
     *
     * @return array
     */
    public function validationData(): array
    {
        $inputs = parent::validationData();

        if (array_key_exists('price', $inputs) && !preg_match('[^0-9]', $inputs['price'])) {
            $inputs['price'] = (float) $this->ArtoEnNumeric($inputs['price']);
        }

        return $inputs;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        // If the id exists this is an edit request
        $id = $this->route('id');

        $product = null;
        if($id){
            $product = Product::find($id);
        }

        $rule_name = $id ? 'nullable' : 'required';

        logger(
            "Check product number - $id",
            [
                'request' => request()->all(),
                'rule_name' => $rule_name,
            ]
        );

        $rules = [
//            'vendor_id' => "{$rule_name}|integer|exists:vendors,id",
            'title' => "{$rule_name}|string|max:200",
            'description' => 'nullable|string|max:5000',
            'price' => "{$rule_name}|numeric|max:100000000",
            'status' => "{$rule_name}|string|in:Published,Draft",
            'category_ids' => 'sometimes|array|min:1',
            'category_ids.*' => 'integer|exists:taxonomies,id',
            'address_ids' => 'sometimes|array|min:1',
            'address_ids.*' => 'integer|exists:addresses,id',
            'media' => ($id ? 'nullable' : 'required_without:keep_media').'|array|min:1',
            'media.*' => "{$rule_name}|image|max:10240",
            'terms' => 'nullable|string|max:5000',
            'type' => "{$rule_name}|in:product,service",
            // Admin only attributes
            'review' => "{$rule_name}|string|in:Approved,Blocked,Pending",
            'url' => 'nullable|string',
        ];

        // If the user is not an admin, remove the fields updated
        // only by admin, so they won't be available when using
        // $request->validated()
        $user = $this->user();
//        if ($user && !$user->isAdmin()) {
//            unset($rules['review']);
//            // The user must be member of provided vendor
//            $rules['vendor_id'] = [
//                "{$rule_name}", 'integer',
//                Rule::exists('user_vendor')->where(function ($query) use ($user) {
//                    $query->where('user_id', $user->id);
//                    $query->where('vendor_id', (int) $this->get('vendor_id'));
//                }),
//            ];
//        }

        // If this is an update request, add sometimes
        // rule to required fields to validate if exists only
        if (is_numeric($id)) {
            // The admin will always send all parameters
            if ($user && !$user->isAdmin()) {
                foreach ($rules as $key => $value) {
                    $rules[$key] = str_replace("{$rule_name}|", 'sometimes|required|', $value);
                }
            }

            // Validate ad media to keep without deletion
            // If not provided all old media will be removed
            // But new media must be provided
            $rules['keep_media'] = ($id ? 'nullable' : 'required_without:media').'|array|min:1';
            $rules['keep_media.*'] = "{$rule_name}|integer|exists:media,id";
        }

        if($this->type == 'product' || $product?->type?->value == 'product')
        {
            $rules = array_merge($rules, [
                'stock' => "{$rule_name}|integer|min:0",
                // 'is_shippable' => "{$rule_name}|boolean",
                // 'min_quantity_per_user' => "{$rule_name}|integer|min:1|max:100",
                // 'max_quantity_per_user' => "{$rule_name}|integer|min:1|max:100|gte:min_quantity_per_user",
                // 'days_of_delivery' => "{$rule_name}|integer|min:1|max:100",
            ]);
        }

        return $rules;
    }

    private function ArtoEnNumeric($string): string
    {
        return strtr(
            $string,
            [
                '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7',
                '٨' => '8', '٩' => '9'
            ]
        );
    }
}
