<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends BaseAdminController
{
    private $orderRepository;

    private $orderService;

    public function __construct(
        OrderService $orderService,
        OrderRepositoryInterface $repository
    ) {
        parent::__construct();

        $this->orderService = $orderService;
        $this->orderRepository = $repository;
    }

    public function index(Request $request)
    {
        $orders = $this->orderRepository->all($request);

        return response()->json($orders);
    }

    public function single($id)
    {
        $order = $this->orderRepository->getOneWithRelations($id);

        return response()->json($order);
    }

    public function update(OrderRequest $request, $id)
    {
        // Update the Order
        $order = $this->orderService->update($id, $request->validated());

        return response()->json($order);
    }

    public function delete($id)
    {
        $this->orderRepository->delete($id);

        return response()->json(['id' => $id]);
    }
}
