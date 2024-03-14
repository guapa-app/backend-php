<?php

namespace App\Services;

use App\Models\Invoice;
use Moyasar\Providers\InvoiceService;
use Moyasar\Providers\PaymentService as MoyasarPaymentService;

class PaymentService
{
    public function generateInvoice($orders, $description, $fees, $taxes)
    {
        $invoice = $this->storeInvoice($orders, $description, $fees, $taxes);

        if (($fees + $taxes) > 0) {
            $moyasarInvoice = $this->moyasarInvoice($invoice);
            $this->updateInvoice($invoice, $moyasarInvoice);
        }

        return $invoice;
    }

    public function storeInvoice($orders, $description, $fees = 0, $taxes = 0)
    {
        $invoice = Invoice::query()->create([
            'order_id'     => $orders->first()->id,
            'status'       => 'initiated',
            'taxes'        => (int) $taxes * 100,
            'amount'       => (int) (($fees + $taxes) * 100),
            'description'  => "You will pay the fees and taxes for \n" . $description,
            'currency'     => config('nova.currency'),
            'callback_url' => config('app.url') . '/api/v1/invoices/change-status',
        ]);

        return $invoice;
    }

    public function moyasarInvoice($invoice)
    {
        $invoiceService = new InvoiceService();
        $invoiceService = $invoiceService->create($invoice->toArray());

        return $invoiceService;
    }

    public function updateInvoice($invoice, $invoiceService)
    {
        $invoice->updateOrFail([
            'invoice_id' => $invoiceService->id,
            'amount_format' => $invoiceService->amountFormat,
            'logo_url' => $invoiceService->logoUrl,
            'url' => $invoiceService->url,
        ]);
    }

    public function refund($order)
    {
        $invoice = $order->invoice;
        $invoiceService = new InvoiceService();
        $invoiceService = $invoiceService->fetch($invoice->invoice_id);

        if (!empty($invoiceService->payments)) {
            if ($invoiceService->payments[0]->status == 'paid') {
                $paymentService = new MoyasarPaymentService();
                $paymentService = $paymentService->fetch($invoiceService->payments[0]->id);
                $paymentService->refund();
                $invoice->update(['status' => 'refunded']);
            } else {
                $invoiceService->cancel();
                $invoice->update(['status' => 'canceled']);
            }
        } else {
            // Expired invoices has no payments.
            $invoice->update(['status' => 'canceled']);
        }

        // delete Invoice pdf
        if ($order->invoice_url) {
            (new PDFService)->deletePDF($order->invoice_url);
            $order->update(['invoice_url' => null]);
        }
    }
}
