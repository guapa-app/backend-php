<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Models\Order;
use App\Models\Setting;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Services\V3_1\OrderService;
use App\Http\Requests\GetOrdersRequest;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Vendor\V3_1\OrderResource;
use App\Http\Resources\Vendor\V3_1\OrderCollection;
use App\Contracts\Repositories\OrderRepositoryInterface;

class OrderController extends BaseApiController
{
    protected $orderRepository;
    protected $orderService;
    public function __construct(OrderRepositoryInterface $orderRepository , OrderService $orderService)
    {
        parent::__construct();

        $this->orderRepository = $orderRepository;
        $this->orderService = $orderService;
    }

    public function index(GetOrdersRequest $request)
    {
        $request->merge(['vendor_id' => $this->user->managerVendorId()]);
        $orders = $this->orderRepository->all($request);

        return OrderCollection::make($orders)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single($id)
    {
        $item = $this->orderService->getOrderDetails((int) $id);

        return OrderResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function update($id, Request $request)
    {
        $this->logReq("Update order number - $id");

        $data = $this->validate($request, [
            'status' => 'required|in:'.implode(',', OrderStatus::availableForUpdate()),
            'cancellation_reason' => 'required_if:status,Cancel Request',
        ]);

        $item = $this->orderService->update((int) $id, $data);

        return OrderResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function showInvoice($id)
    {
        $order = Order::query()
            ->where('hash_id', $id)
            ->firstOrFail();

        $cus_name = $order->user->name;

        $invoice = $order->invoice;

        if (in_array($order->status->value, OrderStatus::notAvailableShowInvoice()) || $invoice == null) {
            return response('Not Available', 405);
        }

        $vat = Setting::getTaxes();

        $order_items = $order->items->map(function ($item) use ($vat) {
            $arr['name'] = $item->title;
            $arr['price'] = $item->amount_to_pay;
            $arr['vat'] = $arr['price'] * $item->taxes / 100;
            $arr['qty'] = $item->quantity;
            $arr['subtotal_with_vat'] = ($arr['price'] + $arr['vat']) * $arr['qty'];

            return $arr;
        });

        return view('invoice', compact('invoice', 'cus_name', 'order_items', 'vat'));
    }
}
