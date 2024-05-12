<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Setting;

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

    public function generateInvoice($orders, $description, $fees, $taxes)
    {
        $invoice = $this->storeInvoice($orders, $description, $fees, $taxes);

        if (($fees + $taxes) > 0) {
            $paymentInvoice = $this->paymentService->create($invoice);
            $this->paymentService->updateInvoice($invoice, $paymentInvoice);
        }

        return $invoice;
    }

    public function storeInvoice($orders, $description, $fees = 0, $taxes = 0)
    {
        $invoice = Invoice::query()->create([
            'order_id'     => $orders->first()->id,
            'status'       => 'initiated',
            'taxes'        => $taxes,
            'amount'       => ($fees + $taxes),
            'description'  => "You will pay the fees and taxes. \n" . $description,
            'currency'     => config('nova.currency'),
            'callback_url' => config('app.url') . '/api/v2/invoices/change-status',
        ]);

        return $invoice;
    }

    public function refund($order)
    {
        $this->paymentService->refund($order);
    }
}
