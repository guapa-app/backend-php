<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Enums\AppointmentOfferEnum;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Requests\GetOrdersRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\User\V3_1\OrderCollection;
use App\Http\Resources\User\V3_1\OrderResource;
use App\Models\Invoice;
use App\Models\User;
use App\Models\UserVendor;
use App\Notifications\AppointmentOfferNotification;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

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
        $request->merge(['user_id' => $this->user->id]);
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
        $order = parent::create($request);

        return OrderResource::make($order)
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

    public function changeInvoiceStatus(Request $request)
    {
        $invoice = Invoice::query()
            ->where('invoice_id', $request->session_id ?? $request->id)
            ->firstOrFail();

        $invoice->updateOrFail(['status' => $request->state ?? $request->status]);

        if ($invoice->status == 'paid') {
            $invoiceable = $invoice->invoiceable;
            $invoiceable->update(['status' => AppointmentOfferEnum::Paid_Application_Fees->value]);

            $userVendors = UserVendor::query()
                ->whereIn('vendor_id', $invoice->invoiceable->details->pluck('vendor_id'))
                ->pluck('user_id');
            $users = User::whereIn('id', $userVendors)->get();

            Notification::send($users, new AppointmentOfferNotification($invoiceable));
        }

//        logger(
//            "Change Invoice Status By Callback URL\n
//            order { $invoice->order_id } <-> Invoice { $invoice->id }",
//            [
//                "\n***payment gateway***" => $request->all(),
//                "\n***invoice***" => $invoice->attributesToArray(),
//            ]
//        );

        return true;
    }
}
