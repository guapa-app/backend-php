<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Requests\GetOrdersRequest;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;

class OrderController extends ApiOrderController
{
    public function index(GetOrdersRequest $request)
    {
        $orders = parent::index($request);

        return response()->json($orders);
    }

    public function single($id)
    {
        $item = parent::single($id);

        return response()->json($item);
    }

    public function create(OrderRequest $request)
    {
        $orders = parent::create($request);

        return response()->json($orders);
    }

    public function update($id, Request $request)
    {
        $item = parent::update($id, $request);

        return response()->json($item);
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
