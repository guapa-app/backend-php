<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'products' => 'required|array|min:1',
            'products.*' => 'required|array',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1|max:10000',
            'products.*.appointment' => 'sometimes|array',
            'products.*.appointment.id' => 'sometimes|required|integer|exists:appointments,id',
            'products.*.appointment.date' => 'sometimes|required|date|after_or_equal:today',
            'products.*.staff_user_id' => 'sometimes|required|integer|exists:users,id',
            'address_id' => 'sometimes|integer|exists:addresses,id',
            'note' => 'nullable|string|max:1000',
            'name' => 'sometimes|required|string|max:60',
            'phone' => 'sometimes|required|string|max:30',
            'status' => 'sometimes|string',
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

        $validator->after(function($validator) {
            $inputProducts = $this->get('products');
            $productIds = array_map(function($product) {
                return $product['id'];
            }, $inputProducts);

            // Products require address_id and Services require appointment_date
            $productsCount = Product::whereIn('id', $productIds)->where('type', 'product')->count();

            if ($productsCount > 0 && $this->get('address_id') == null) {
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
