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
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationGroup = 'Shop';

    public static function infolist(Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist
            ->schema([
                Components\TextEntry::make('id')
                    ->label('Invoice ID'),
                Components\TextEntry::make('invoice_id')
                    ->label('Payment Invoice ID'),
                Components\TextEntry::make('invoiceable_id')
                    ->label('Order ID')
                    ->formatStateUsing(fn ($record) => $record->invoiceable_type === Order::class ? $record->invoiceable_id : '-')
                    ->url(fn ($record) => $record->invoiceable_type === Order::class ? route('filament.admin.resources.shop.orders.view', $record->invoiceable_id) : null, shouldOpenInNewTab: true)
                    ->color('primary'),
                Components\TextEntry::make('invoiceable_type')
                    ->label('Type')
                    ->formatStateUsing(fn ($record) => match ($record->invoiceable_type) {
                        Order::class => 'Order',
                        MarketingCampaign::class => 'Marketing Campaign',
                        AppointmentOffer::class => 'Appointment Offer',
                        default => '-',
                    }),
                Components\TextEntry::make('vendor_name')
                    ->label('Vendor Name'),
                Components\TextEntry::make('vendor_reg_num')
                    ->label('Vendor Reg Number'),
                Components\TextEntry::make('invoiceable.user.name')
                    ->label('Customer Name')
                    ->formatStateUsing(fn ($record) => $record->invoiceable_type === Order::class ? $record->invoiceable?->user?->name : '-'),
                Components\TextEntry::make('invoiceable.vendor.name')
                    ->label('Vendor Name')
                    ->formatStateUsing(fn ($record) => $record->invoiceable_type === Order::class ? $record->invoiceable?->vendor?->name : '-'),
                Components\TextEntry::make('amount')
                    ->money('SAR')
                    ->label('Total Amount'),
                Components\TextEntry::make('taxes')
                    ->money('SAR')
                    ->label('Taxes'),
                Components\TextEntry::make('amount_without_taxes')
                    ->money('SAR')
                    ->label('Amount Without Taxes'),
                Components\TextEntry::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'initiated' => 'gray',
                        'pending' => 'warning',
                        'paid' => 'success',
                        'refunded' => 'danger',
                    }),
                Components\TextEntry::make('currency')
                    ->label('Currency'),
                Components\TextEntry::make('description')
                    ->label('Description'),
                Components\TextEntry::make('created_at')
                    ->dateTime()
                    ->label('Created At'),
                Components\TextEntry::make('updated_at')
                    ->dateTime()
                    ->label('Updated At'),
            ]);
    }

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
            ->modifyQueryUsing(fn ($query) => $query->with(['invoiceable.user', 'invoiceable.vendor']))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_id')
                    ->searchable()
                    ->label('Invoice ID'),
                Tables\Columns\TextColumn::make('invoiceable_id')
                    ->label('Order ID')
                    ->formatStateUsing(fn ($record) => $record->invoiceable_type === Order::class ? $record->invoiceable_id : '-')
                    ->url(fn ($record) => $record->invoiceable_type === Order::class ? route('filament.admin.resources.shop.orders.view', $record->invoiceable_id) : null, shouldOpenInNewTab: true)
                    ->color('primary')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoiceable.user.name')
                    ->label('Customer')
                    ->formatStateUsing(fn ($record) => $record->invoiceable_type === Order::class ? $record->invoiceable?->user?->name : '-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoiceable.vendor.name')
                    ->label('Vendor')
                    ->formatStateUsing(fn ($record) => $record->invoiceable_type === Order::class ? $record->invoiceable?->vendor?->name : '-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('SAR')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'initiated' => 'gray',
                        'pending' => 'warning',
                        'paid' => 'success',
                        'refunded' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('invoiceable_type')
                    ->label('Type')
                    ->formatStateUsing(fn ($record) => match ($record->invoiceable_type) {
                        Order::class => 'Order',
                        MarketingCampaign::class => 'Marketing Campaign',
                        AppointmentOffer::class => 'Appointment Offer',
                        default => '-',
                    })
                    ->badge()
                    ->color(fn ($record) => match ($record->invoiceable_type) {
                        Order::class => 'primary',
                        MarketingCampaign::class => 'warning',
                        AppointmentOffer::class => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('taxes')
                    ->money('SAR')
                    ->label('Taxes')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_without_taxes')
                    ->money('SAR')
                    ->label('Amount (No Tax)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->label('Currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
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
                Tables\Filters\SelectFilter::make('invoiceable_type')
                    ->label('Type')
                    ->options([
                        Order::class => 'Order',
                        MarketingCampaign::class => 'Marketing Campaign',
                        AppointmentOffer::class => 'Appointment Offer',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'initiated' => 'Initiated',
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'refunded' => 'Refunded',
                    ]),
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
            'view' => Pages\ViewInvoice::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
