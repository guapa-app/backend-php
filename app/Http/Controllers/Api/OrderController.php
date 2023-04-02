<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Http\Requests\GetOrdersRequest;
use App\Http\Requests\OrderRequest;
use App\Models\Invoice;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\PDFService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Orders
 *
 */
class OrderController extends BaseApiController
{
    protected $orderRepository;
    protected $orderService;

    public function __construct(OrderRepositoryInterface $orderRepository, OrderService $orderService)
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
     * @queryParam page number for pagination Example: 2
     * @queryParam perPage Results to fetch per page Example: 15
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(GetOrdersRequest $request)
    {
        if (!$request->has('vendor_id')) {
            $request->merge([
                'user_id' => $this->user->id,
            ]);
        } elseif (!$this->user->hasVendor((int)$request->get('vendor_id'))) {
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
     * @param int $id
     * @return JsonResponse
     */
    public function single($id)
    {
        $order = $this->orderService->getOrderDetails((int)$id);
        return response()->json($order);
    }

    /**
     * Create order
     *
     * @responseFile 200 responses/orders/create.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @param OrderRequest $request
     * @return JsonResponse
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
     * @param int $id
     * @param OrderRequest $request
     *
     * @return JsonResponse
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

        $order = $this->orderService->update((int)$id, $data);

        return response()->json($order);
    }

    public function changeInvoiceStatus(Request $request)
    {
        $invoice = Invoice::query()->where('invoice_id', $request->id)->firstOrFail();
        $invoice->updateOrFail(['status' => $request->status]); // paid

        $invoice->order->updateOrFail(['status' => "Accepted"]);

        logger("Change Invoice Status By Moyasar Callback URL\n
         order { $invoice->order_id } <-> Invoice { $invoice->id }",
            [
                "\nmoyasar" => $request->all(),
                "\ninvoice" => $invoice,
            ]
        );

        return true;
    }

    public function printPDF($id)
    {
        $order = Order::query()->findOrFail($id);

        if ($order->status != "Accepted")
            return response()->json(['message' => __('You must pay first.')], 405);

        $order->invoice_url = (new PDFService)->generatePDF($order);
        $order->save();

        logger("Print PDF\n order { $order->id }", ["\norder" => $order]);

        return response()->json($order->invoice_url);
    }
}
