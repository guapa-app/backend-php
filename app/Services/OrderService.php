<?php

namespace App\Services;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Enums\OrderStatus;
use App\Enums\OrderTypeEnum;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\UserVendor;
use App\Notifications\AddVendorClientNotification;
use App\Notifications\OrderUpdatedNotification;
use App\Services\V3_1\AppointmentService;
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

            // Group products by vendor to create an order for each vendor
            $keyedProducts = $products->groupBy('vendor_id');

            $orders = new Collection;
            $now = now();
            $orderItems = [];
            $userIds = [];

            foreach ($keyedProducts as $vendorId => $vendorProducts) {
                $data['vendor_id'] = $vendorId;

                $productsTitles = $vendorProducts->pluck('title')->implode(' - ');

                $data['total'] = (float) $vendorProducts->sum(function ($product) use (
                    $data,
                    $now,
                    $vendorId,
                    &
                    $orderItems,
                    &$userIds
                ) {
                    $inputItem = Arr::first($data['products'], fn($value) => (int) $value['id'] === $product->id);

                    if (isset($inputItem['appointment'])) {
                        $appointment = Appointment::find($inputItem['appointment']['id']);

                        if ($appointment->vendor_id !== $vendorId) {
                            abort(400, 'Invalid appointment selected');
                        }

                        $inputItem['appointment']['from_time'] = $appointment->from_time;
                        $inputItem['appointment']['to_time'] = $appointment->to_time;
                    }

                    if ($product->offer) {
                        $product->price -= ($product->price * ($product->offer->discount / 100));
                        $product->price = round($product->price, 2);
                    }

                    $finalPrice = $inputItem['quantity'] * $product->price;

                    $productCategory = $product->taxonomies()->first();

                    if ($productCategory?->fees) {
                        $productFees = $productCategory->fees;
                        $itemAmountToPay = ($productFees / 100) * $finalPrice;
                        $this->fees += $itemAmountToPay;
                    } else {
                        $itemAmountToPay = $productCategory?->fixed_price;
                        $this->fees += $itemAmountToPay;
                    }

                    $userIds[] = $inputItem['staff_user_id'] ?? null;

                    $qrcodeData = [
                        'hash_id' => $product->hash_id,
                        'title' => $product->title,
                        'amount_to_pay' => $itemAmountToPay,
                        'vendor_name' => $product->vendor->name,
                    ];

                    $orderItems[] = [
                        'user_id' => $inputItem['staff_user_id'] ?? null,
                        'product_id' => $product->id,
                        'offer_id' => optional($product->offer)->id,
                        'quantity' => $inputItem['quantity'],
                        'amount' => $product->price,
                        'amount_to_pay' => $itemAmountToPay,
                        'taxes' => $this->taxesPercentage,
                        'title' => $product->title,
                        'qr_code_link' => (new QrCodeService())->generate($qrcodeData),
                        'appointment' => isset($inputItem['appointment']) ? json_encode($inputItem['appointment']) : null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    return $finalPrice;
                });

                // Check that provided user ids belong to vendor
                $userIds = array_unique($userIds);

                if (!$this->checkVendorUsers($vendorId, $userIds)) {
                    abort(400, 'Invalid staff provided for vendor');
                }

                $order = $this->repository->create($data);

                $orderItems = array_map(function ($item) use ($order) {
                    $item['order_id'] = $order->id; // Merge the order ID into each item

                    return $item;
                }, $orderItems);

                OrderItem::query()->insert($orderItems);

                if ($data['type'] == OrderTypeEnum::Appointment->value) {
                    (new AppointmentService())->createAppointment($order, $data['appointments']);
                }

                $order->load('items', 'address', 'user', 'vendor');

                $this->taxes = ($this->taxesPercentage / 100) * $this->fees;
                // Generate invoice for the first order (since there's only one order per vendor)
                $invoice = $this->paymentService->generateInvoice($order, $productsTitles, $this->fees, $this->taxes);
                $order->invoice_url = $invoice->url;
                $order->save();

                $orders->push($order);
                $this->fees = $this->taxes = 0;
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

        $user = $order->user;
        $vendor = $order->vendor;
        // add the client to vendor client list if the order is used
        if ($data['status'] == (OrderStatus::Used)->value) {
            $vendor->clients()->firstOrCreate(['user_id' => $user->id]);
            Notification::send($user, new AddVendorClientNotification($vendor, false));
        }
        if ($data['status'] == (OrderStatus::Canceled)->value && ($order->invoice != null)) {
            $this->paymentService->refund($order);
        }

        Notification::send($user, new OrderUpdatedNotification($order));

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
                $error = __('api.not_available_for_action',
                    ['status' => __('api.order_statuses.'.$order->status->value)]);
            }
        } elseif ($req_status == (OrderStatus::Return_Request)->value) {
            $error = $this->checkUserAuthorization($order, $user);

            if ($order->status != OrderStatus::Delivered) {
                $error = __('api.not_available_for_action',
                    ['status' => __('api.order_statuses.'.$order->status->value)]);
            }
        }

        if ($order->status->value === $req_status) {
            $error = __('api.order_status_req_status_error',
                ['status' => __('api.order_statuses.'.$order->status->value)]);
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
