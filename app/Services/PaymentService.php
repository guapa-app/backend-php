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

    public function generateInvoice($invoiceable, $description, $fees, $taxes)
    {
        $invoice = $this->createInvoice($invoiceable, $description, $fees, $taxes);

        if (($fees + $taxes) > 0) {
            $paymentInvoice = $this->paymentService->create($invoice);
            $this->paymentService->updateInvoice($invoice, $paymentInvoice);
        }

        return $invoice;
    }

    private function createInvoice($invoiceable, $description, $fees, $taxes)
    {
        $invoiceData = [
            'invoiceable_id'   => $invoiceable->id,
            'invoiceable_type' => get_class($invoiceable),
            'status'           => 'initiated',
            'taxes'            => $taxes,
            'amount'           => ($fees + $taxes),
            'description'      => $description,
            'currency'         => config('nova.currency'),
        ];

        if ($invoiceable instanceof Order) {
            $invoiceData['callback_url'] = config('app.url') . '/api/v2/invoices/change-status';
            $invoiceData['description'] = "Order Invoice: \n" . $description;
        } elseif ($invoiceable instanceof MarketingCampaign) {
            $invoiceData['callback_url'] = config('app.url') . '/api/v3/campaigns/change-invoice-status';
            $invoiceData['description'] = "Marketing Campaign Invoice: \n" . $description;
        } else {
            throw new InvalidArgumentException(__('Unsupported invoice type'));
        }

        return Invoice::query()->create($invoiceData);
    }

    public function refund($order)
    {
        $this->paymentService->refund($order);
    }
}
