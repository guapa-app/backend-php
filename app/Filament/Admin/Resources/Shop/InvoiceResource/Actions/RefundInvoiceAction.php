<?php

namespace App\Filament\Admin\Resources\Shop\InvoiceResource\Actions;

use App\Enums\AppointmentOfferEnum;
use App\Enums\MarketingCampaignStatus;
use App\Enums\OrderStatus;
use App\Models\AppointmentOffer;
use App\Models\MarketingCampaign;
use App\Models\Order;
use App\Services\LoyaltyPointsService;
use App\Services\PaymentService;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RefundInvoiceAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'refund';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-arrow-path')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Refund Invoice')
            ->modalDescription('Are you sure you want to refund this invoice? This action cannot be undone.')
            ->modalSubmitActionLabel('Yes, refund invoice')
            ->visible(fn (Model $record) => $record->status === 'paid')
            ->action(function (Model $record) {
                $this->process($record);
            });
    }

    protected function process(Model $record): void
    {
        $loyaltyPointsService = resolve(LoyaltyPointsService::class);
        $paymentService = resolve(PaymentService::class);

        $invoiceable = $record->invoiceable;

        if (!$invoiceable) {
            Notification::make()
                ->danger()
                ->title('Unable to process refund')
                ->body("Unable to process refund for invoice {$record->id}")
                ->send();
            return;
        }

        try {
            DB::beginTransaction();

            $paymentService->refund($invoiceable);

            $this->updateModelStatus($invoiceable);

            if ($invoiceable instanceof Order) {
                $loyaltyPointsService->returnPurchasePoints($invoiceable);
            }

            $record->update(['status' => 'refunded']);

            DB::commit();

            Notification::make()
                ->success()
                ->title('Refund Successful')
                ->body("Refund processed successfully for invoice {$record->id}")
                ->send();
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->danger()
                ->title('Refund Failed')
                ->body("Error processing refund for invoice {$record->id}: {$e->getMessage()}")
                ->send();
        }
    }

    protected function updateModelStatus($model): void
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
}
