<?php

namespace App\Filament\Admin\Resources\Shop;


use App\Enums\OrderStatus;
use App\Filament\Admin\Resources\Shop\OrderResource\Actions\SendWhatsAppReminderAction;
use App\Filament\Admin\Resources\Shop\OrderResource\Pages;
use App\Filament\Admin\Resources\Shop\OrderResource\RelationManagers;
use App\Filament\Admin\Resources\Shop\OrderResource\Widgets\OrdersStatusChart;
use App\Filament\Admin\Resources\Shop\OrderResource\Widgets\OrderStats;
use App\Models\Order;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-pound';

    protected static ?string $navigationGroup = 'Shop';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\TextEntry::make('id'),
                Components\TextEntry::make('vendor.name'),
                Components\TextEntry::make('address.title'),
                Components\TextEntry::make('user.name'),
                Components\TextEntry::make('user.phone'),
                Components\TextEntry::make('total'),
                Components\TextEntry::make('status'),
                Components\TextEntry::make('note'),
                Components\TextEntry::make('cancellation_reason'),
                Components\TextEntry::make('coupon.code'),
                Components\TextEntry::make('discount_amount'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hash_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vendor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
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
            ->defaultPaginationPageOption(10)
            ->paginated([10])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                SendWhatsAppReminderAction::make()
                    ->visible(fn (Order $record) => $record->status == OrderStatus::Pending),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
