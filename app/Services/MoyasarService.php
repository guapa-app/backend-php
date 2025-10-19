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

    public function refund($model)
    {
        if (!method_exists($model, 'invoice')) {
            throw new \InvalidArgumentException("Model must have an 'invoice' relationship");
        }

        $invoice = $model->invoice;
        if (!$invoice) {
            throw new \InvalidArgumentException("No invoice found for this model.");
        }

        if (!$model->payment_id) {
            throw new \InvalidArgumentException("No payment ID associated with this model.");
        }

        $paymentService = new MoyasarPaymentService();
        $payment = $paymentService->fetch($model->payment_id);

        if ($payment->status === 'paid') {
            $payment->refund();
            $newStatus = 'refunded';
        } else {
            $newStatus = 'canceled';
        }

        // update status of the model
        $invoice->update(['status' => $newStatus]);

        // delete Invoice pdf
        if (property_exists($model, 'invoice_url') && $model->invoice_url) {
            (new PDFService)->deletePDF($model->invoice_url);
            $model->update(['invoice_url' => null]);
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

    /**
     * Check if the payment paid successfully.
     *
     * @param  mixed $payment_id
     * @return bool
     */
    public function isPaymentPaidSuccessfully($payment_id)
    {
        try {
            $paymentService = new MoyasarPaymentService();
            $payment = $paymentService->fetch($payment_id);

            if ($payment->status == 'paid') {
                return true;
            }
            return false;

        } catch (\Exception $e) {
            // Log the error message if needed
            \Log::error('Payment fetch failed: ' . $e->getMessage());

            // Return false or throw a custom error message
            return false;
        }
    }

}
