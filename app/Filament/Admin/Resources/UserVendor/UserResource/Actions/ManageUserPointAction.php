<?php

namespace App\Filament\Admin\Resources\UserVendor\UserResource\Actions;

use App\Services\AdminUserPointHistoryService;
use App\Services\LoyaltyPointsService;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class ManageUserPointAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Points')
            ->form([
                Forms\Components\Select::make('action_type')
                    ->options([
                        'addition' => 'Add Points',
                        'deduction' => 'Deduct Points',
                    ])
                    ->native(false)
                    ->rules('required'),

                Forms\Components\TextInput::make('points')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->rules('required', 'integer'),

                Forms\Components\TextInput::make('reason')
                    ->nullable()
                    ->hint('Reason for addition or deduction (optional)'),
            ])
            ->action(function ($record, array $data) {
                $loyaltyPointsService = resolve(LoyaltyPointsService::class);
                $adminUserPointHistoryService = resolve(AdminUserPointHistoryService::class);

                if ($data['action_type'] === 'addition') {
                    $loyaltyPointsService->addAdminUserPoints($record, $data['points']);
                } else {
                    $loyaltyPointsService->deductAdminUserPoints($record, $data['points']);
                }

                $adminUserPointHistoryService->addHistory($record->id, auth()->id(), $data['action_type'], $data['points'], $data['reason']);

                Notification::make()
                    ->title('Points updated successfully.')
                    ->success()
                    ->send();
            })
            ->icon('heroicon-o-bolt')
            ->modalHeading('Manage User Points')
            ->modalSubmitActionLabel('Save points');
    }
}
