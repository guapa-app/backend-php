<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Enums\OrderStatus;
use App\Http\Requests\GetOrdersRequest;
use App\Http\Requests\OrderRequest;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Setting;
use App\Services\OrderService;
use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * @group Orders
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
     * Orders listing.
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
     * @return Collection
     */
    public function index(GetOrdersRequest $request)
    {
        if (!$request->has('vendor_id')) {
            $request->merge([
                'user_id' => $this->user->id,
            ]);
        } elseif (!$this->user->hasVendor((int) $request->get('vendor_id'))) {
            abort(403, 'Forbidden');
        }

        return $this->orderRepository->all($request);
    }

    /**
     * Get Order by id.
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
     * @return Order
     */
    public function single($id)
    {
        return $this->orderService->getOrderDetails((int) $id);
    }

    /**
     * Create order.
     *
     * @responseFile 200 responses/orders/create.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @param OrderRequest $request
     * @return Collection
     */
    public function create(OrderRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $this->user->id;

        return $this->orderService->create($data);
    }

    /**
     * Update order.
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
     * @return Order
     */
    public function update($id, Request $request)
    {
        $this->logReq("Update order number - $id");

        $data = $this->validate($request, [
            'status' => 'required|in:' . implode(',', OrderStatus::availableForUpdate()),
            'cancellation_reason' => 'required_if:status,Cancel Request',
        ]);

        return $this->orderService->update((int) $id, $data);
    }

    public function changeInvoiceStatus(Request $request)
    {
        $invoice = Invoice::query()->where('invoice_id', $request->id)->firstOrFail();
        $invoice->updateOrFail(['status' => $request->status]); // paid

        $invoice->order->updateOrFail(['status' => 'Accepted']);

        logger(
            "Change Invoice Status By Moyasar Callback URL\n
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

        if ($order->status != OrderStatus::Accepted) {
            return response()->json(['message' => __('You must pay first.')], 405);
        }

        $order->invoice_url = (new PDFService)->generatePDF($order);
        $order->save();

        logger("Print PDF\n order { $order->id }", ["\norder" => $order]);

        return $order->invoice_url;
    }

    public function showInvoice($id)
    {
        $order = Order::query()
            ->where('hash_id', $id)
            ->firstOrFail();

        $cus_name = $order->user->name;

        if ($order->status != OrderStatus::Accepted) {
            return response()->json(['message' => __('You must pay first.')], 405);
        }

        $invoice = $order->invoice;

        abort_if($invoice == null, 405, 'There is no invoice for this order');

        $vat = Setting::getTaxes();

        $order_items = $order->items->map(function ($item) use ($vat) {
            $product = $item->product;
            $arr['name'] = $product->title;
            $arr['price'] = $product->categories->first()->fees / 100 * $item->amount;
            $arr['vat'] = $arr['price'] * $vat / 100;
            $arr['qty'] = $item->quantity;
            $arr['subtotal_with_vat'] = ($arr['price'] + $arr['vat']) * $arr['qty'];

            return $arr;
        });

        return view('invoice', compact('invoice', 'cus_name', 'order_items', 'vat'));
    }
}
