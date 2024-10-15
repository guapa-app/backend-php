<?php

namespace App\Services;

use App\Models\AppointmentOffer;
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
        } elseif ($invoiceable instanceof AppointmentOffer) {
            $invoiceData['callback_url'] = config('app.url').'/api/user/v3.1/invoices/change-status';
        } else {
            throw new InvalidArgumentException(__('Unsupported invoice type'));
        }

        return Invoice::query()->create($invoiceData);
    }

    public function refund($model)
    {
        // if the model has payment id and the payment is paid successfully
        // check the payment_getway if it wallet return it to the user wallet else refund it to the payment_getway
        if (!empty($model->payment_id)) {
            if ($model->payment_gateway == 'wallet') {
                $model->user->wallet += $model->total;
                $model->user->save();
            } else {
                $this->paymentService->refund($model);
            }
        }

    }

    /**
     * Check if the payment paid successfully.
     *
     * @param  mixed $payment_id
     * @return bool
     */
    public function isPaymentPaidSuccessfully($payment_id)
    {
        return $this->paymentService->isPaymentPaidSuccessfully($payment_id);
    }
}
