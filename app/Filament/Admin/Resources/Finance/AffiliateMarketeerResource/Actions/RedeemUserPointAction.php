<?php

namespace App\Filament\Admin\Resources\Finance\AffiliateMarketeerResource\Actions;

use App\Services\AdminUserPointHistoryService;
use App\Services\LoyaltyPointsService;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class RedeemUserPointAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Redeem Points')
            ->form([
                Forms\Components\TextInput::make('points')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->rules('required|integer'),
            ])
            ->action(function ($record, array $data) {
                $loyaltyPointsService = resolve(LoyaltyPointsService::class);

                $loyaltyPointsService->convertPointsToBalance($record->id, $data['points'], true);

                Notification::make()
                    ->title('Points redeemed successfully.')
                    ->success()
                    ->send();
            })
            ->icon('heroicon-o-arrow-path')
            ->modalHeading('Redeem User Points')
            ->modalSubmitActionLabel('Redeem points');
    }
}
