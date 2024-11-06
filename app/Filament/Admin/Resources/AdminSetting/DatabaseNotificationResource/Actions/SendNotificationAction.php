<?php

namespace App\Filament\Admin\Resources\AdminSetting\DatabaseNotificationResource\Actions;

use App\Models\User;
use App\Models\Vendor;
use App\Services\DatabaseNotificationService;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SendNotificationAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Send Notification')
            ->form([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('summary')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->native(false)
                    ->options([
                        'user' => 'User',
                        'vendor' => 'Vendor',
                        'android' => 'Android',
                        'ios' => 'iOS',
                    ])
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('recipients')
                    ->native(false)
                    ->multiple()
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name}")
                    ->options(function (Builder $query, callable $get) {
                        if ($get('type') === 'user') {
                            return User::all()->pluck('name', 'id')->toArray();
                        }
                        if ($get('type') === 'vendor') {
                            return Vendor::all()->pluck('name', 'id')->toArray();
                        }

                        return [];
                    })
                    ->preload()
                    ->hidden(function (callable $get) {
                        return !($get('type') == 'user' || $get('type') == 'vendor');
                    })
                    ->searchable(),
            ])
            ->action(function (array $data) {
                $this->newNotification(data: $data);

                Notification::make()
                    ->title('Notification Sent Successfully.')
                    ->success()
                    ->send();
            })
            ->icon('heroicon-o-bolt')
            ->modalHeading('Sent Notification')
            ->modalSubmitActionLabel('Sent Notification');
    }

    protected function newNotification(array $data)
    {
        $notificationService = app(DatabaseNotificationService::class);

        return $notificationService->create($data);
    }
}
