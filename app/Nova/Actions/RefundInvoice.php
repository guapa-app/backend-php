<?php

namespace App\Nova\Actions;

use App\Enums\AppointmentOfferEnum;
use App\Enums\MarketingCampaignStatus;
use App\Enums\OrderStatus;
use App\Enums\TransactionType;
use App\Models\AppointmentOffer;
use App\Models\MarketingCampaign;
use App\Models\Order;
use App\Services\LoyaltyPointsService;
use App\Services\PaymentService;
use App\Services\WalletService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class RefundInvoice extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $loyaltyPointsService = resolve(LoyaltyPointsService::class);
        $paymentService = resolve(PaymentService::class);
        foreach ($models as $invoice) {
            $invoiceable = $invoice->invoiceable;

            if (!$invoiceable) {
                return Action::danger('Unable to process refund for invoice ' . $invoice->id);
            }
            try {
                DB::beginTransaction();

                $paymentService->refund($invoiceable);

                $this->updateModelStatus($invoiceable);

                if ($invoiceable instanceof Order) {
                    $loyaltyPointsService->returnPurchasePoints($invoiceable);
                }

                $invoice->update(['status' => 'refunded']);

                DB::commit();

                return Action::message('Refund processed successfully for invoice ' . $invoice->id);
            } catch (\Exception $e) {
                DB::rollBack();
                return Action::danger('Error processing refund for invoice ' . $invoice->id . ': ' . $e->getMessage());
            }
        }
    }


    /**
     * Update the status of the model based on its type.
     *
     * @param mixed $model
     */
    protected function updateModelStatus($model)
    {
        if ($model instanceof Order) {
            $model->update(['status' => OrderStatus::Canceled]);
        } elseif ($model instanceof MarketingCampaign) {
            $model->update(['status' => MarketingCampaignStatus::Refunded]);
        } elseif ($model instanceof AppointmentOffer) {
            $model->update(['status' => AppointmentOfferEnum::Refunded]);
        } else {
            throw new \InvalidArgumentException('Unsupported model type for refund');
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
