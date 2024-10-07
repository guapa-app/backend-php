<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\Common\PaymentStatusRequest;
use App\Services\MarketingCampaignService;
use App\Services\PaymentService;
use App\Services\V3_1\AppointmentOfferService;
use App\Services\V3_1\OrderService;
use Illuminate\Support\Facades\Log;

class PaymentController extends BaseApiController
{
    protected $orderService;
    protected $marketingCampaignService;
    protected $appointmentOfferService;
    protected $paymentService;

    public function __construct(
        OrderService $orderService,
        MarketingCampaignService $marketingCampaignService,
        AppointmentOfferService $appointmentOfferService,
        PaymentService $paymentService
    )
    {
        parent::__construct();
        $this->orderService = $orderService;
        $this->marketingCampaignService = $marketingCampaignService;
        $this->appointmentOfferService = $appointmentOfferService;
        $this->paymentService = $paymentService;
    }

    /**
     * Change payment status.
     *
     * @responseFile 200 responses/payment/status_changed.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     *
     * @param  PaymentStatusRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePaymentStatus(PaymentStatusRequest $request)
    {
        $data = $request->validated();
        $payment_id = $request->payment_id;
//        dd($payment_id);
        if ($this->paymentService->isPaymentPaidSuccessfully($payment_id)) {
            $type = $request->type;
            try {
                switch ($type) {
                    case 'order':
                        $this->orderService->changeOrderStatus($data);
                        break;
                    case 'campaign':
                        $this->marketingCampaignService->changePaymentStatus($data);
                        break;
                    case 'appointment':
                        $this->appointmentOfferService->changePaymentStatus($data);
                        break;
                    default:
                        return $this->errorJsonRes([], __('api.invalid_type'));
                }

                return $this->successJsonRes([], __('api.payment_status_changed'));
            } catch (\Exception $e) {
                Log::error('Error changing payment status: ' . $e->getMessage());
                return $this->errorJsonRes([], __('api.error_payment_status'));
            }
        } else {
            return $this->errorJsonRes([], __('Payment details are incorrect. Please check your payment again.'));
        }
    }
}
