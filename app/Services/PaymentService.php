<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\MarketingCampaign;
use App\Models\Order;
use App\Models\Setting;
use InvalidArgumentException;

class PaymentService
{
    private $paymentService;

    public function __construct()
    {
        $payment_option = Setting::getPaymentGatewayMethod();

        $this->paymentService = match ($payment_option) {
            'ottu' => new OttuService,
            default => new MoyasarService,
        };
    }

    public function generateInvoice($billable, $description, $fees, $taxes)
    {
        $invoice = $this->createInvoice($billable, $description, $fees, $taxes);

        if (($fees + $taxes) > 0) {
            $paymentInvoice = $this->paymentService->create($invoice);
            $this->paymentService->updateInvoice($invoice, $paymentInvoice);
        }

        return $invoice;
    }

//    public function storeInvoice($order, $description, $fees = 0, $taxes = 0)
//    {
//        $invoice = Invoice::query()->create([
//            'order_id'     => $order->id,
//            'status'       => 'initiated',
//            'taxes'        => $taxes,
//            'amount'       => ($fees + $taxes),
//            'description'  => "You will pay the fees and taxes. \n" . $description,
//            'currency'     => config('nova.currency'),
//            'callback_url' => config('app.url') . '/api/v2/invoices/change-status',
//        ]);
//
//        return $invoice;
//    }

    private function createInvoice($billable, $description, $fees, $taxes)
    {
        $invoiceData = [
            'status'       => 'initiated',
            'taxes'        => $taxes,
            'amount'       => ($fees + $taxes),
            'description'  => $description,
            'currency'     => config('nova.currency'),
        ];

        if ($billable instanceof Order) {
            $invoiceData['order_id'] = $billable->id;
            $invoiceData['callback_url'] = config('app.url') . '/api/v2/invoices/change-status';
            $invoiceData['description'] = "Order Invoice: \n" . $description;
        } elseif ($billable instanceof MarketingCampaign) {
            $invoiceData['marketing_campaign_id'] = $billable->id;
            $invoiceData['callback_url'] = config('app.url') . '/api/v3/campaigns/change-invoice-status';
            $invoiceData['description'] = "Marketing Campaign Invoice: \n" . $description;
        } else {
            throw new InvalidArgumentException("Unsupported billable type");
        }

        return Invoice::query()->create($invoiceData);
    }

    public function refund($order)
    {
        $this->paymentService->refund($order);
    }
}
