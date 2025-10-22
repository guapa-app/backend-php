<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Services\PaymentService;
use App\Services\GiftCardService;
use App\Services\V3_1\BkamConsultationService;
use Illuminate\Support\Facades\Log;
use App\Services\ConsultationService;
use App\Services\MarketingCampaignService;
use App\Services\V3_1\OrderPaymentService;
use App\Services\V3_1\AppointmentOfferService;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\Common\PayByWalletRequest;
use App\Http\Requests\V3_1\Common\PaymentStatusRequest;

class PaymentController extends BaseApiController
{
    protected $orderPaymentService;
    protected $marketingCampaignService;
    protected $appointmentOfferService;
    protected $paymentService;
    protected $consultationService;
    protected $giftCardService;
    protected $bkamConsultationService;
    public function __construct(
        OrderPaymentService $orderPaymentService,
        MarketingCampaignService $marketingCampaignService,
        AppointmentOfferService $appointmentOfferService,
        PaymentService $paymentService,
        ConsultationService $consultationService,
        GiftCardService $giftCardService,
        BkamConsultationService $bkamConsultationService
    ) {
        parent::__construct();
        $this->orderPaymentService = $orderPaymentService;
        $this->marketingCampaignService = $marketingCampaignService;
        $this->appointmentOfferService = $appointmentOfferService;
        $this->paymentService = $paymentService;
        $this->consultationService = $consultationService;
        $this->giftCardService = $giftCardService;
        $this->bkamConsultationService = $bkamConsultationService;
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

        if ($this->paymentService->isPaymentPaidSuccessfully($payment_id)) {
            $type = $request->type;
            try {
                switch ($type) {
                    case 'order':
                        $this->orderPaymentService->changeOrderStatus($data);
                        break;
                    case 'campaign':
                        $this->marketingCampaignService->changePaymentStatus($data);
                        break;
                    case 'appointment':
                        $this->appointmentOfferService->changePaymentStatus($data);
                        break;
                    case 'consultation':
                        $this->consultationService->changePaymentStatus($data);
                        break;
                    case 'gift_card':
                        $this->giftCardService->changePaymentStatus($data);
                        break;
                    case 'bkam_consultation':
                        $this->bkamConsultationService->changePaymentStatus($data);
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

    /**
     * Pay Via Wallet
     *
     * @param  mixed $request
     * @return void
     */
    public function payViaWallet(PayByWalletRequest $request)
    {
        $data = $request->validated();
        $data['payment_gateway'] = 'wallet';
        $data['status'] = 'paid';
        try {
            $user = $request->user();
            $type = $request->type;

            switch ($type) {
                case 'order':
                    $this->orderPaymentService->payViaWallet($user, $data);
                    break;
                case 'campaign':
                    $this->marketingCampaignService->payViaWallet($user, $data);
                    break;
                case 'appointment':
                    $this->appointmentOfferService->payViaWallet($user, $data);
                    break;
                case 'consultation':
                    $this->consultationService->payViaWallet($user, $data);
                    break;
                case 'gift_card':
                    $this->giftCardService->payViaWallet($user, $data);
                    break;
                case 'bkam_consultation':
                    $this->bkamConsultationService->payViaWallet($user, $data);
                    break;
                default:
                    return $this->errorJsonRes([], __('api.invalid_type'));
            }

            return $this->successJsonRes([], __('api.paid_successfully'));
        } catch (\Exception $e) {
            Log::error('Error changing payment status: ' . $e->getMessage());
            return $this->errorJsonRes([], $e->getMessage() ?: __('api.error_payment_status'));
        }
    }
}
