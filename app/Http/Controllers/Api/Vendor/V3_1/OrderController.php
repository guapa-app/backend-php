<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Requests\GetOrdersRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Services\V3\OrderService;
use Illuminate\Http\Request;

class OrderController extends ApiOrderController
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(GetOrdersRequest $request)
    {
        $orders = parent::index($request);

        return OrderCollection::make($orders)
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

    public function create(OrderRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth('api')->user()->id;
        $orders = $this->orderService->create($data);

        return OrderCollection::make($orders)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function update($id, Request $request)
    {
        $item = parent::update($id, $request);

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
