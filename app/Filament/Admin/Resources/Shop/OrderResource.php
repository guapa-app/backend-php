<?php

namespace App\Filament\Admin\Resources\Shop;


use App\Enums\OrderStatus;
use App\Filament\Admin\Resources\Shop\OrderResource\Actions\SendWhatsAppReminderAction;
use App\Filament\Admin\Resources\Shop\OrderResource\Pages;
use App\Filament\Admin\Resources\Shop\OrderResource\RelationManagers;
use App\Filament\Admin\Resources\Shop\OrderResource\Widgets\OrdersStatusChart;
use App\Filament\Admin\Resources\Shop\OrderResource\Widgets\OrderStats;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Taxonomy;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;

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
                Components\TextEntry::make('user.phone')->label('Phone'),
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
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['vendor', 'user', 'items.product.taxonomies']))
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
                    ->label('Phone')
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
                Tables\Filters\SelectFilter::make('vendor')
                    ->label('Vendor')
                    ->relationship('vendor', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('All Vendors'),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(
                        Taxonomy::whereIn('type', ['category', 'specialty'])
                            ->get()
                            ->pluck('title_en_ar', 'id')
                            ->toArray()
                    )
                    ->searchable()
                    ->preload()
                    ->placeholder('All Categories')
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('items.product.taxonomies', function (Builder $subQuery) use ($data) {
                                $subQuery->where('taxonomies.id', $data['value']);
                            });
                        }
                        return $query;
                    }),
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
