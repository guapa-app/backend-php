<?php

namespace App\Filament\Admin\Resources\UserVendor\UserResource\RelationManagers;

use App\Enums\OrderStatus;
use App\Filament\Admin\Resources\Shop\OrderResource\Actions\SendWhatsAppReminderAction;
use App\Models\Order;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\TextEntry::make('id'),
                Components\TextEntry::make('address.title'),
                Components\TextEntry::make('user.name'),
                Components\TextEntry::make('user.phone'),
                Components\TextEntry::make('total'),
                Components\TextEntry::make('status'),
                Components\TextEntry::make('note'),
                Components\TextEntry::make('cancellation_reason'),
                Components\TextEntry::make('coupon_id'),
                Components\TextEntry::make('discount_amount'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hash_id')
                    ->searchable(),
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
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
}
