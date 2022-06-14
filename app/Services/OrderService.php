<?php

namespace App\Services;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Models\Appointment;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\UserVendor;
use App\Notifications\OrderNotification;
use App\Notifications\OrderUpdatedNotification;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;

class OrderService {

	private $repository;

	public function __construct(OrderRepositoryInterface $repository)
	{
		$this->repository = $repository;
	}

	public function create(array $data): Collection
	{
		$productIds = array_map(function($product) {
            return $product['id'];
        }, $data['products']);

        $products = Product::whereIn('id', $productIds)->get();

        // Group products by vendor to create an order for each vendor
        $keyedProducts = [];
        foreach ($products as $k => $product) {
        	$keyedProducts[$product->vendor_id][] = $product;
        }

        $orders = new Collection;
        $now = now();

        foreach ($keyedProducts as $vendorId => $vendorProducts) {
        	$data['vendor_id'] = $vendorId;
        	$data['total'] = array_sum(array_map(function($product) use ($data) {
	        	$inputItem = Arr::first($data['products'], function($value, $key) use ($product) {
	        		return (int)($value['id']) === $product->id;
	        	});

                if($product->offer != null) {
                    $product['price'] -= ($product['price'] * ($product->offer->discount / 100));
                    $product['price'] = round($product['price'], 2);
                }

	        	return $inputItem['quantity'] * $product['price'];
	        }, $vendorProducts));

	        $order = $this->repository->create($data);

	        $items = array_map(function($product) use ($data, $order, $now, $vendorId) {
	        	$inputItem = Arr::first($data['products'], function($value, $key) use ($product) {
	        		return (int)($value['id']) === $product->id;
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
	        $userIds = array_filter(array_map(function($item) {
	        	return $item['user_id'];
	        }, $items));

	        if (!$this->checkVendorUsers($vendorId, $userIds)) {
	        	abort(400, 'Invalid staff provided for vendor');
	        }

	        OrderItem::insert($items);

	        $order->load('items', 'address', 'user', 'vendor');

	        Notification::send($order->vendor->staff, new OrderNotification($order));

	        $orders->push($order);
        }

        return $orders;
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

		if ($order->status !== 'Pending') {
			$error = 'This order has been ' . $order->status;
		} elseif ($order->status === $status) {
			$error = 'The order is already ' . $status;
		} elseif ($status === 'Canceled' && $order->user_id !== $user->id) {
			$error = 'You are not authorized to cancel this order';
		} elseif (in_array($status, ['Accepted', 'Rejected'])) {
			// This should be an employee of a vendor authorized to accept or reject the order.
			$authorizedVendorIds = Product::select('vendor_id')->whereIn('id', function($q) use ($order) {
				$q->select('product_id')->from('order_items')->where('order_id', $order->id);
			})->pluck('vendor_id')->toArray();

			if (!$user->hasAnyVendor($authorizedVendorIds)) {
				$error = 'You are not authorized to ' . str_replace('ed', '', $status) . ' this order';
			}
		}

		if (isset($error)) {
			throw ValidationException::withMessages([
				'status' => $error,
			]);
		}
	}
}
