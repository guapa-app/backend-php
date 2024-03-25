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
            'redirect_url' => '',
            'webhook_url' => $data->callback_url,
        ];
    }

    public function create(Invoice $invoice): ?array
    {
        $invoice = $this->dataHandler($invoice);

        try {
            $res = Http::asForm()
                ->withHeaders(['Authorization' => $this->apiKey])
                ->acceptJson()
                ->post($this->baseUrl, $invoice);

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

    public function update($session_id, $data)
    {
    }

    public function get($session_id)
    {
    }

    public function refund($order)
    {
    }

    public function updateInvoice($invoice, $invoiceService)
    {
        $invoice->updateOrFail([
            'url'           => $invoiceService['checkout_url'],
            'invoice_id'    => $invoiceService['session_id'],
            'amount_format' => $invoiceService['amount'] . ' ' . $invoiceService['currency_code'],
        ]);
    }
}
