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
use App\Notifications\OrderUpdatedNotification;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class OrderService
{
    protected $repository;
    protected $paymentService;
    protected $productFees;
    protected $taxesPercentage;
    protected $isService = false;

    public function __construct(OrderRepositoryInterface $repository, PaymentService $payment_service)
    {
        $this->repository = $repository;
        $this->paymentService = $payment_service;
        $this->productFees = Setting::getProductFees();
        $this->taxesPercentage = Setting::getTaxes();
        $this->fees = 0;
        $this->taxes = 0;
    }

    public function create(array $data): Collection
    {
        return DB::transaction(function () use ($data) {
            $productIds = array_column($data['products'], 'id');
            $products = Product::whereIn('id', $productIds)->get();

            $productsTitles = $products->pluck('title')->implode(' - ');

            // Group products by vendor to create an order for each vendor
            $keyedProducts = $products->groupBy('vendor_id');

            $orders = new Collection;
            $now = now();

            foreach ($keyedProducts as $vendorId => $vendorProducts) {
                $data['vendor_id'] = $vendorId;
                $data['total'] = (float) $vendorProducts->sum(function ($product) use ($data) {
                    $inputItem = Arr::first($data['products'], fn($value) => (int) $value['id'] === $product->id);

                    if ($product->offer) {
                        $product->price -= ($product->price * ($product->offer->discount / 100));
                        $product->price = round($product->price, 2);
                    }

                    $finalPrice = $inputItem['quantity'] * $product->price;

                    if ($product->type === ProductType::Service) {
                        $this->isService = true;
                        $productFees = optional($product->categories()->first())->fees ?? 0;
                        $this->fees += ($productFees / 100) * $finalPrice;
                    } else {
                        $this->fees += ($this->productFees / 100) * $finalPrice;
                    }

                    return $finalPrice;
                });

                $order = $this->repository->create($data);

                $items = $vendorProducts->map(function ($product) use ($data, $order, $now, $vendorId) {
                    $inputItem = Arr::first($data['products'], fn($value) => (int) $value['id'] === $product->id);

                    if (isset($inputItem['appointment'])) {
                        $appointment = Appointment::find($inputItem['appointment']['id']);

                        if ($appointment->vendor_id !== $vendorId) {
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
                });

                // Check that provided user ids belong to vendor
                $userIds = $items->pluck('user_id')->filter()->all();

                if (!$this->checkVendorUsers($vendorId, $userIds)) {
                    abort(400, 'Invalid staff provided for vendor');
                }

                OrderItem::insert($items->toArray());

                $order->load('items', 'address', 'user', 'vendor');
                $orders->push($order);

                if ($this->isService) {
                    $this->taxes = ($this->taxesPercentage / 100) * $this->fees;

                    // Generate invoice for the first order (since there's only one order per vendor)
                    $invoice = $this->paymentService->generateInvoice($orders, $productsTitles, $this->fees, $this->taxes);
                    $orders->first()->invoice_url = $invoice->url;
                }
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

        if ($data['status'] == (OrderStatus::Canceled)->value && ($order->invoice != null)) {
            $this->paymentService->refund($order);
        }

        Notification::send($order->user, new OrderUpdatedNotification($order));

        return $order;
    }

    public function getOrderDetails(int $id): Order
    {
        $user = app('cosmo')->user();
        $order = Order::withSingleRelations()->where('id', $id)->firstOrFail();
        if ($order->user_id != $user->id && !$user->hasVendor($order->vendor_id)) {
            abort(403, __('api.order_not_available_for', ['status' => __('view_details')]));
        }

        return $order;
    }

    public function validateStatus(Order $order, string $req_status): void
    {
        $user = app('cosmo')->user();

        if ($user->isAdmin()) {
            return;
        }

        if (in_array($req_status, OrderStatus::availableForUpdateByVendor())) {
            $authorizedVendorIds = Product::query()->select('vendor_id')->whereIn('id', function ($q) use ($order) {
                $q->select('product_id')->from('order_items')->where('order_id', $order->id);
            })->pluck('vendor_id')->toArray();

            if (!$user->hasAnyVendor(array_merge($authorizedVendorIds, [$order->vendor_id]))) {
                $error = __('api.order_authorization_error');
            }
        } elseif ($req_status == (OrderStatus::Cancel_Request)->value) {
            $error = $this->checkUserAuthorization($order, $user);

            if ($order->created_at->addDays(14)->toDateString() < Carbon::today()->toDateString()) {
                $error = __('api.cancel_order_error');
            } elseif (in_array($order->status->value, OrderStatus::notAvailableForCancle())) {
                $error = __('api.not_available_for_action', ['status' => __('api.order_statuses.' . $order->status->value)]);
            }
        } elseif ($req_status == (OrderStatus::Return_Request)->value) {
            $error = $this->checkUserAuthorization($order, $user);

            if ($order->status != OrderStatus::Delivered) {
                $error = __('api.not_available_for_action', ['status' => __('api.order_statuses.' . $order->status->value)]);
            }
        }

        if ($order->status->value === $req_status) {
            $error = __('api.order_status_req_status_error', ['status' => __('api.order_statuses.' . $order->status->value)]);
        }

        if (isset($error)) {
            throw ValidationException::withMessages([
                'status' => $error,
            ]);
        }
    }

    private function checkUserAuthorization($order, $user): ?string
    {
        return ($order->user_id !== $user->id)
            ? __('api.order_authorization_error')
            : null;
    }
}
