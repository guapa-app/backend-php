<?php

namespace App\Services;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Enums\OrderStatus;
use App\Enums\ProductType;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\UserVendor;
use App\Notifications\OrderNotification;
use App\Notifications\OrderUpdatedNotification;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class OrderService
{
    private $repository;
    private $payment_service;
    private $product_fees;
    private $taxes_percentage;
    private $is_service = false;

    public function __construct(OrderRepositoryInterface $repository, PaymentService $payment_service)
    {
        $this->repository = $repository;
        $this->payment_service = $payment_service;
        $this->product_fees = Setting::getProductFees();
        $this->taxes_percentage = Setting::getTaxes();
        $this->fees = 0;
        $this->taxes = 0;
    }

    public function create(array $data): Collection
    {
        return DB::transaction(function () use ($data) {
            $productIds = array_map(function ($product) {
                return $product['id'];
            }, $data['products']);

            $products = Product::whereIn('id', $productIds)->get();

            $products_titles = $products->implode('title', ' - ');

            // Group products by vendor to create an order for each vendor
            $keyedProducts = [];
            foreach ($products as $k => $product) {
                $keyedProducts[$product->vendor_id][] = $product;
            }

            $orders = new Collection;
            $now = now();

            foreach ($keyedProducts as $vendorId => $vendorProducts) {
                $data['vendor_id'] = $vendorId;
                $data['total'] = (float) array_sum(array_map(function ($product) use ($data) {
                    $inputItem = Arr::first($data['products'], function ($value, $key) use ($product) {
                        return (int) ($value['id']) === $product->id;
                    });

                    if ($product->offer != null) {
                        $product['price'] -= ($product['price'] * ($product->offer->discount / 100));
                        $product['price'] = round($product['price'], 2);
                    }

                    $final_price = $inputItem['quantity'] * $product['price'];

                    if ($product->type == ProductType::Service) {
                        $this->is_service = true;

                        $product_fees = optional($product->categories()->first())->fees;

                        $this->fees += ($product_fees / 100) * $final_price;
                    } else {
                        $this->fees += ($this->product_fees / 100) * $final_price;
                    }

                    return $final_price;
                }, $vendorProducts));

                $order = $this->repository->create($data);

                $items = array_map(function ($product) use ($data, $order, $now, $vendorId) {
                    $inputItem = Arr::first($data['products'], function ($value, $key) use ($product) {
                        return (int) ($value['id']) === $product->id;
                    });

                    if (isset($inputItem['appointment'])) {
                        $appointment = Appointment::find($inputItem['appointment']['id']);

                        if ($appointment->vendor_id != $vendorId) {
                            $order->delete();
                            abort(400, 'Invalid appointment selected');
                        }

                        $inputItem['appointment']['from_time'] = $appointment->from_time;
                        $inputItem['appointment']['to_time'] = $appointment->to_time;
                    }

                    return [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'offer_id' => optional($product->offer)->id,
                        'amount' => $product->price,
                        'quantity' => $inputItem['quantity'],
                        'appointment' => isset($inputItem['appointment']) ? json_encode($inputItem['appointment']) : null,
                        'user_id' => $inputItem['staff_user_id'] ?? null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }, $vendorProducts);

                // Check that provided user ids belong to vendor
                $userIds = array_filter(array_map(function ($item) {
                    return $item['user_id'];
                }, $items));

                if (!$this->checkVendorUsers($vendorId, $userIds)) {
                    abort(400, 'Invalid staff provided for vendor');
                }

                OrderItem::insert($items);

                $order->load('items', 'address', 'user', 'vendor');

                // Notification::send($order->vendor->staff, new OrderNotification($order));

                $orders->push($order);
            }

            if ($this->is_service) {
                $this->taxes = ($this->taxes_percentage / 100) * $this->fees;

                // it 'll be one order at all for one vendor
                $invoice = $this->payment_service->generateInvoice($orders, $products_titles, $this->fees, $this->taxes);

                // return invoice url with order response
                $orders->first()['invoice_url'] = $invoice->url;
            }

            return $orders;
        });
    }

    public function checkVendorUsers(int $vendorId, array $userIds): bool
    {
        return UserVendor::where('vendor_id', $vendorId)
            ->whereIn('user_id', $userIds)->count() === count(array_unique($userIds));
    }

    public function update(int $id, array $data): Order
    {
        $order = $this->repository->getOneOrFail($id);
        $this->validateStatus($order, $data['status']);
        $order = $this->repository->update($order, $data);
        if ($data['status'] == 'Canceled' && ($order->invoice != null)) {
            $this->payment_service->refund($order);
        }
        Notification::send($order->user, new OrderUpdatedNotification($order));

        return $order;
    }

    public function getOrderDetails(int $id): Order
    {
        $user = app('cosmo')->user();
        $order = Order::withSingleRelations()->where('id', $id)->firstOrFail();
        if ($order->user_id != $user->id && !$user->hasVendor($order->vendor_id)) {
            abort(403, 'You cannot view details for this order');
        }

        return $order;
    }

    public function validateStatus(Order $order, string $status): void
    {
        $user = app('cosmo')->user();

        if ($user->isAdmin()) {
            return;
        }

        if (in_array($status, OrderStatus::availableForUpdateByVendor())) {
            $authorizedVendorIds = Product::query()->select('vendor_id')->whereIn('id', function ($q) use ($order) {
                $q->select('product_id')->from('order_items')->where('order_id', $order->id);
            })->pluck('vendor_id')->toArray();

            if (!$user->hasAnyVendor(array_merge($authorizedVendorIds, [$order->vendor_id]))) {
                $error = 'You are not authorized to ' . str_replace('ed', '', $status) . ' this order';
            }
        } elseif ($status == (OrderStatus::Cancel_Request)->value) {
            if ($order->user_id !== $user->id) {
                $error = 'You are not authorized to cancel this order';
            } elseif ($order->is_used) {
                $error = __('api.cancel_used_order_error');
            } elseif ($order->created_at->addDays(14)->toDateString() < Carbon::today()->toDateString()) {
                $error = __('api.cancel_order_error');
            } elseif (in_array($order->status, [OrderStatus::Rejected, OrderStatus::Canceled])) {
                $error = __('api.cancel_rejected_canceled_order_error');
            }
        }

        if ($order->status->value === $status) {
            $error = 'The order is already ' . $status;
        }

        if (isset($error)) {
            throw ValidationException::withMessages([
                'status' => $error,
            ]);
        }
    }
}
