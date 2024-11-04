<?php

namespace App\Filament\Admin\Resources\Shop;

use App\Filament\Admin\Resources\Shop\InvoiceResource\Actions\RefundInvoiceAction;
use App\Filament\Admin\Resources\Shop\InvoiceResource\Pages;
use App\Models\AppointmentOffer;
use App\Models\Invoice;
use App\Models\MarketingCampaign;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(12),
                Forms\Components\TextInput::make('taxes')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('currency')
                    ->required()
                    ->maxLength(5),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('callback_url')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('invoice_id')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'initiated' => 'gray',
                        'pending' => 'warning',
                        'paid' => 'success',
                        'refunded' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('invoice_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoiceable')
                    ->label('Invoiceable')
                    ->formatStateUsing(fn ($record) => match ($record->invoiceable_type) {
                        Order::class => 'Order: ' . $record->invoiceable_id,
                        MarketingCampaign::class => 'Marketing Campaign: ' . $record->invoiceable_id,
                        AppointmentOffer::class => 'Appointment Offer: ' . $record->invoiceable_id,
                        default => '-',
                    })
                    ->url(fn ($record) => match ($record->invoiceable_type) {
                        Order::class => route('filament.admin.resources.shop.orders.view', $record->invoiceable_id),
                        MarketingCampaign::class => route('filament.admin.resources.shop.marketing-campaigns.view', $record->invoiceable_id),
                        AppointmentOffer::class => route('filament.admin.resources.shop.appointment-offers.view', $record->invoiceable_id),
                        default => null,
                    }, shouldOpenInNewTab: true)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                RefundInvoiceAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
