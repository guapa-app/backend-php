<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'note'                              => 'nullable|string|max:1000',
            'name'                              => 'sometimes|required|string|max:60',
            'phone'                             => 'sometimes|required|string|max:30',
            'status'                            => 'sometimes|string',
            'products'                          => 'required|array|min:1',
            'address_id'                        => 'sometimes|integer|exists:addresses,id',

            'products.*'                        => 'required|array',
            'products.*.id'                     => 'required|integer|exists:products,id',
            'products.*.quantity'               => 'required|integer|min:1|max:10000',
            'products.*.appointment'            => 'sometimes|array',
            'products.*.appointment.id'         => 'sometimes|required|integer|exists:appointments,id',
            'products.*.appointment.date'       => 'sometimes|required|date|after_or_equal:today',
            'products.*.staff_user_id'          => 'sometimes|required|integer|exists:users,id',
        ];

        if ($this->user() && $this->user()->isAdmin()) {
            unset($rules['products']);
            unset($rules['products.*']);
            unset($rules['products.*.id']);
            unset($rules['products.*.quantity']);
        } else {
            unset($rules['status']);
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        if (!$validator->passes() || $this->user()->isAdmin()) {
            return;
        }

        $validator->after(function ($validator) {
            $inputProducts = $this->get('products');
            $productIds = array_map(function ($product) {
                return $product['id'];
            }, $inputProducts);

            // Products require address_id and Services require appointment_date
            $productQuery = Product::query()->whereIn('id', $productIds);

            $types = $productQuery->pluck('type')->toArray();
            $is_service = in_array("service", $types);
            $is_product = in_array("product", $types);

            // can't request service and product at same time
            if ($is_service && $is_product) {
                $validator->errors()->add('order', __('api.multi_items_type'));
            }

            // if in requested items is service and services from multi vendors
            if ((count(array_unique($productQuery->pluck('vendor_id')->toArray())) > 1) && $is_service) {
                $validator->errors()->add('order', __('api.multi_vendors'));
            }

            if ($productQuery->where('type', 'product')->count() > 0 && $this->get('address_id') == null) {
                $validator->errors()->add('address_id', __('api.address_required'));
            }

            foreach ($inputProducts as $product) {
                if (!isset($product['appointment'])) {
                    continue;
                }

                $appointment = $product['appointment'];

                if (!isset($appointment['id'], $appointment['date'])) {
                    $validator->errors()->add('products', __('api.appointment_required'));
                    break;
                }

                $isBooked = OrderItem::where([
                    'product_id' => $product['id'],
                    'appointment->id' => $appointment['id'],
                    'appointment->date' => $appointment['date'],
                ])->exists();

                if ($isBooked) {
                    $validator->errors()->add('products', __('api.appointment_reserved'));
                }
            }
        });
    }
}
