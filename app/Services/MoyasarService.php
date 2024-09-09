<?php

namespace App\Services;

use App\Models\Invoice;
use Moyasar\Providers\InvoiceService;
use Moyasar\Providers\PaymentService as MoyasarPaymentService;

class MoyasarService
{
    private function dataHandler($data)
    {
        $data = $data->attributesToArray();

        /*
         * Convert the amount to the smallest currency unit as an integer.
         * For example, 1 Riyal = 100 Halals.
         * Use number_format to avoid floating-point precision issues and convert the result to an integer.
         * Examples: 167.56 becomes 16756, and 9.2 becomes 920.
         */
        $data['amount'] = (int) number_format($data['amount'] * 100, decimal_separator: '', thousands_separator: '');

        return $data;
    }

    public function create(Invoice $invoice)
    {
        $invoice = $this->dataHandler($invoice);

        $invoiceService = new InvoiceService();
        $invoiceService = $invoiceService->create($invoice);

        return $invoiceService;
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

    public function updateInvoice($invoice, $invoiceService)
    {
        $invoice->updateOrFail([
            'url'           => $invoiceService->url,
            'logo_url'      => $invoiceService->logoUrl,
            'invoice_id'    => $invoiceService->id,
            'amount_format' => $invoiceService->amountFormat,
        ]);
    }
}
