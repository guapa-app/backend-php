<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\GetOrdersRequest;
use App\Services\OrderService;
use App\Contracts\Repositories\OrderRepositoryInterface;

/**
 * @group Orders
 * 
 */
class OrderController extends BaseApiController
{
	protected $orderRepository;
	protected $orderService;
	
	public function __construct(OrderRepositoryInterface $orderRepository,
		OrderService $orderService)
	{
        parent::__construct();
        
		$this->orderRepository = $orderRepository;
		$this->orderService = $orderService;
	}
 
    /**
     * Orders listing
     *
     * @responseFile 200 responses/orders/list.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 403 scenario="Cannot view orders for provided vendor" 403 responses/errors/403.json
     *
     * @queryParam vendor_id integer Get orders for specific vendor. Example: 1
     * @queryParam status string. `Accepted`, `Pending`, `Canceled`, `Rejected`
     * @queryParam page Page number for pagination Example: 2
     * @queryParam perPage Results to fetch per page Example: 15
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GetOrdersRequest $request)
    {
        if ( ! $request->has('vendor_id')) {
            $request->merge([
                'user_id' => $this->user->id,
            ]);
        } elseif (!$this->user->hasVendor((int) $request->get('vendor_id'))) {
            abort(403, 'Forbidden');
        }

    	$orders = $this->orderRepository->all($request);
    	return response()->json($orders);
    }

    /**
     * Get Order by id
     * 
     * @unauthenticated
     *
     * @responseFile 200 responses/orders/details.json
     * @responseFile 403 scenario="Unauthorized to view order details" responses/errors/403.json
     * @responseFile 404 scenario="Vendor not found" responses/errors/404.json
     *
     * @urlParam id required Order id
     * 
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function single($id)
    {
    	$order = $this->orderService->getOrderDetails((int) $id);
    	return response()->json($order);
    }

    /**
     * Create order
     *
     * @responseFile 200 responses/orders/create.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * 
     * @param  \App\Http\Requests\OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(OrderRequest $request)
    {
    	$data = $request->validated();
    	$data['user_id'] = $this->user->id;
    	$orders = $this->orderService->create($data);
    	return response()->json($orders);
    }

    /**
     * Update order
     *
     * @urlParam id required Order id
     * @bodyParam status string required. One of `Accepted`, `Rejected`, `Canceled`
     *
     * @responseFile 200 responses/orders/update.json
     * @responseFile 404 scenario="Order not found" responses/errors/404.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * 
     * @param  int    $id
     * @param  \App\Http\Requests\OrderRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
    	$data = $this->validate($request, [
            'status' => 'required|in:Canceled,Accepted,Rejected',
        ]);
        logger("Check update order number - $id",
            [
                'requestall' => $request->all(),
                'data' => $data,
            ]
        );

    	$order = $this->orderService->update((int) $id, $data);

    	return response()->json($order);
    }
}
