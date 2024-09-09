<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Requests\GetOrdersRequest;
use App\Http\Resources\V3_1\OrderResource;
use App\Services\V3\OrderService;

class OrderController extends ApiOrderController
{
    protected $orderService;

    public function __construct(OrderRepositoryInterface $orderRepository, OrderService $orderService)
    {
        parent::__construct($orderRepository, $orderService);
        $this->orderService = $orderService;
    }

    public function index(GetOrdersRequest $request)
    {
        $request->merge(['vendor_id' => $this->user->userVendor?->vendor_id]);
        $orders = parent::index($request);

        return OrderResource::collection($orders)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single($id)
    {
        $item = parent::single($id);

        return OrderResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function printPDF($id)
    {
        $url = parent::printPDF($id);

        return $this->successJsonRes(['url' => $url], __('api.success'));
    }

    public function showInvoice($id)
    {
        return parent::showInvoice($id);
    }
}
