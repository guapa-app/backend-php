<?php

namespace App\Filament\Admin\Resources\Shop\InvoiceResource\RelationManagers;

use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrderRelationManager extends RelationManager
{
    protected static string $relationship = 'order';

    public static function canViewForRecord($record, $pageClass): bool
    {
        return $record->invoiceable_type === \App\Models\Order::class;
    }

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Order Details';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Order Information')
                    ->schema([
                        Components\TextEntry::make('order.id')
                            ->label('Order ID')
                            ->url(fn ($record) => route('filament.admin.resources.shop.orders.edit', $record->order?->id), shouldOpenInNewTab: true)
                            ->suffix('Edit')
                            ->color('primary'),
                        Components\TextEntry::make('hash_id')
                            ->label('Hash ID'),
                        Components\TextEntry::make('total')
                            ->money('SAR')
                            ->label('Order Total'),
                        Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn($state): string => match ($state?->value ?? $state) {
                                'Pending' => 'warning',
                                'Accepted' => 'success',
                                'Used' => 'info',
                                'Rejected' => 'danger',
                                'Canceled' => 'gray',
                                'Expired' => 'gray',
                                'Delivered' => 'success',
                                'Completed' => 'success',
                                'Prepare for delivery' => 'warning',
                                'Shipping' => 'info',
                                'Return Request' => 'warning',
                                'Returned' => 'danger',
                                'Cancel Request' => 'warning',
                                default => 'gray',
                            }),
                        Components\TextEntry::make('type')
                            ->label('Order Type'),
                        Components\TextEntry::make('note')
                            ->label('Order Note'),
                        Components\TextEntry::make('cancellation_reason')
                            ->label('Cancellation Reason'),
                        Components\TextEntry::make('created_at')
                            ->dateTime()
                            ->label('Order Created'),
                        Components\TextEntry::make('updated_at')
                            ->dateTime()
                            ->label('Order Updated'),
                    ])
                    ->columns(2),

                Components\Section::make('Customer Information')
                    ->schema([
                        Components\TextEntry::make('user.name')
                            ->label('Customer Name'),
                        Components\TextEntry::make('user.phone')
                            ->label('Customer Phone'),
                        Components\TextEntry::make('user.email')
                            ->label('Customer Email'),
                        Components\TextEntry::make('name')
                            ->label('Order Name'),
                        Components\TextEntry::make('phone')
                            ->label('Order Phone'),
                    ])
                    ->columns(2),

                Components\Section::make('Vendor Information')
                    ->schema([
                        Components\TextEntry::make('vendor.name')
                            ->label('Vendor Name'),
                        Components\TextEntry::make('vendor.reg_number')
                            ->label('Vendor Reg Number'),
                        Components\TextEntry::make('vendor.phone')
                            ->label('Vendor Phone'),
                        Components\TextEntry::make('vendor.email')
                            ->label('Vendor Email'),
                        Components\TextEntry::make('country.name')
                            ->label('Country'),
                    ])
                    ->columns(2),

                Components\Section::make('Address Information')
                    ->schema([
                        Components\TextEntry::make('address.title')
                            ->label('Address Title'),
                        Components\TextEntry::make('address.address')
                            ->label('Address'),
                        Components\TextEntry::make('address.city')
                            ->label('City'),
                        Components\TextEntry::make('address.state')
                            ->label('State'),
                        Components\TextEntry::make('address.postal_code')
                            ->label('Postal Code'),
                    ])
                    ->columns(2),

                Components\Section::make('Payment Information')
                    ->schema([
                        Components\TextEntry::make('payment_gateway')
                            ->label('Payment Gateway'),
                        Components\TextEntry::make('payment_id')
                            ->label('Payment ID'),
                        Components\TextEntry::make('coupon.code')
                            ->label('Coupon Code'),
                        Components\TextEntry::make('discount_amount')
                            ->money('SAR')
                            ->label('Discount Amount'),
                        Components\TextEntry::make('fees')
                            ->money('SAR')
                            ->label('Fees'),
                        Components\TextEntry::make('vendor_wallet')
                            ->label('Vendor Wallet'),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable()
                    ->label('Order ID'),
                Tables\Columns\TextColumn::make('hash_id')
                    ->searchable()
                    ->label('Hash ID'),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('vendor.name')
                    ->searchable()
                    ->label('Vendor'),
                Tables\Columns\TextColumn::make('total')
                    ->money('SAR')
                    ->sortable()
                    ->label('Total'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($state): string => match ($state?->value ?? $state) {
                        'Pending' => 'warning',
                        'Accepted' => 'success',
                        'Used' => 'info',
                        'Rejected' => 'danger',
                        'Canceled' => 'gray',
                        'Expired' => 'gray',
                        'Delivered' => 'success',
                        'Completed' => 'success',
                        'Prepare for delivery' => 'warning',
                        'Shipping' => 'info',
                        'Return Request' => 'warning',
                        'Returned' => 'danger',
                        'Cancel Request' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Accepted' => 'Accepted',
                        'Used' => 'Used',
                        'Rejected' => 'Rejected',
                        'Canceled' => 'Canceled',
                        'Expired' => 'Expired',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn ($record) => route('filament.admin.resources.shop.orders.view', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make()
                    ->url(fn ($record) => route('filament.admin.resources.shop.orders.edit', $record))
                    ->openUrlInNewTab(),
            ]);
    }
}
