<?php

namespace App\Services;

use App\Models\Invoice;
use Exception;
use Illuminate\Support\Facades\Http;

class OttuService
{
    private $baseUrl;

    private $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('ottu.base_uri');
        $this->apiKey = config('ottu.api_key');
    }

    private function dataHandler($data)
    {
        $customer = $data->order->user;

        return [
            'amount' => $data->amount,
            'currency_code' => config('nova.currency'),
            'pg_codes' => [
                'credit-card-bsf',
            ],
            'type' => 'e_commerce',
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone,
            'extra' => [
                'full_name' => $customer->name,
                'transaction_reference_no' => 'Order' . $data->order_id,
            ],
            'order_no' => 'guapa-order-' . $data->order_id,
            'redirect_url' => config('app.url') . '/status=paid&message=APPROVED',
            'webhook_url' => $data->callback_url,
        ];
    }

    public function create(Invoice $invoice): ?array
    {
        $invoice = $this->dataHandler($invoice);

        try {
            $res = Http::withoutVerifying()->asForm()
                ->withHeaders(['Authorization' => $this->apiKey])
                ->acceptJson()
                ->post("$this->baseUrl/b/checkout/v1/pymt-txn", $invoice);

            if ($res->status() != 201) {
                \App\Helpers\Common::logReq('Ottu CREATE SESSION ERR LOG', $res->json());
                abort(400, 'something went wrong');
            }

            return $res->json();
        } catch (Exception $e) {
            \App\Helpers\Common::logReq('Ottu CREATE SESSION ERR LOG', $e->getMessage());

            return null;
        }
    }

    public function update($session_id, $data): ?array
    {
        $data = $this->dataHandler($data);

        try {
            $res = Http::withoutVerifying()->asForm()
                ->withHeaders(['Authorization' => $this->apiKey])
                ->acceptJson()
                ->patch("$this->baseUrl/b/checkout/v1/pymt-txn/$session_id", $data);

            if ($res->status() != 200) {
                \App\Helpers\Common::logReq('Ottu UPDATE SESSION ERR LOG', $res->json());
                abort(400, 'something went wrong');
            }

            return $res->json();
        } catch (Exception $e) {
            \App\Helpers\Common::logReq('Ottu UPDATE SESSION ERR LOG', $e->getMessage());

            return null;
        }
    }

    public function get($session_id): ?array
    {
        try {
            $res = Http::withoutVerifying()->asForm()
                ->withHeaders(['Authorization' => $this->apiKey])
                ->acceptJson()
                ->get("$this->baseUrl/b/checkout/v1/pymt-txn/$session_id");

            if ($res->status() != 200) {
                \App\Helpers\Common::logReq('Ottu GET SESSION ERR LOG', $res->json());
                abort(400, 'something went wrong');
            }

            return $res->json();
        } catch (Exception $e) {
            \App\Helpers\Common::logReq('Ottu GET SESSION ERR LOG', $e->getMessage());

            return null;
        }
    }

    public function refund($order)
    {
        $invoice = $order->invoice;

        $data = [
            'order_no' => 'guapa-order-' . $order->id,
            'session_id' => $invoice->invoice_id,
            'operation' => 'refund',
        ];

        try {
            $res = Http::withoutVerifying()->asForm()
                ->withHeaders(['Authorization' => $this->apiKey])
                ->acceptJson()
                ->post("$this->baseUrl/b/pbl/v2/operation", $data);

            if ($res->status() != 200) {
                \App\Helpers\Common::logReq('Ottu REFUND SESSION ERR LOG', $res->json());
                abort(400, 'something went wrong');
            }

            if ($res->json()['result'] == 'success') {
                $invoice->update(['status' => 'refunded']);
            }

            // delete Invoice pdf if exists
            if ($order->invoice_url) {
                (new PDFService)->deletePDF($order->invoice_url);
                $order->update(['invoice_url' => null]);
            }
        } catch (Exception $e) {
            \App\Helpers\Common::logReq('Ottu REFUND SESSION ERR LOG', $e->getMessage());

            return null;
        }
    }

    public function updateInvoice($invoice, $invoiceService)
    {
        $invoice->updateOrFail([
            'url'           => $invoiceService['checkout_url'],
            'invoice_id'    => $invoiceService['session_id'],
            'amount_format' => $invoiceService['amount'] . ' ' . $invoiceService['currency_code'],
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
        return false;
    }
}
