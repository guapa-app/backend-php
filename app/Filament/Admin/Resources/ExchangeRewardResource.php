<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ExchangeRewardResource\Pages;
use App\Models\ExchangeReward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExchangeRewardResource extends Resource
{
    protected static ?string $model = ExchangeReward::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Loyalty System';

    protected static ?string $navigationLabel = 'Exchange Rewards';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\Select::make('type')
                    ->options([
                        ExchangeReward::TYPE_COUPON => 'Coupon',
                        ExchangeReward::TYPE_GIFT_CARD => 'Gift Card',
                        ExchangeReward::TYPE_SHIPPING_DISCOUNT => 'Shipping Discount',
                        ExchangeReward::TYPE_PRODUCT_DISCOUNT => 'Product Discount',
                        ExchangeReward::TYPE_CASH_CREDIT => 'Cash Credit',
                    ])
                    ->required()
                    ->reactive(),

                Forms\Components\TextInput::make('points_required')
                    ->required()
                    ->numeric()
                    ->minValue(1),

                Forms\Components\TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->step(0.01),

                Forms\Components\TextInput::make('max_uses_per_user')
                    ->numeric()
                    ->helperText('Leave empty for unlimited uses per user'),

                Forms\Components\TextInput::make('total_available')
                    ->numeric()
                    ->helperText('Leave empty for unlimited total availability'),

                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active')
                    ->required(),

                Forms\Components\DateTimePicker::make('expires_at')
                    ->helperText('Leave empty if reward never expires'),

                Forms\Components\KeyValue::make('metadata')
                    ->helperText('Additional configuration data for the reward')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => ExchangeReward::TYPE_COUPON,
                        'success' => ExchangeReward::TYPE_GIFT_CARD,
                        'warning' => ExchangeReward::TYPE_SHIPPING_DISCOUNT,
                        'danger' => ExchangeReward::TYPE_PRODUCT_DISCOUNT,
                        'info' => ExchangeReward::TYPE_CASH_CREDIT,
                    ]),

                Tables\Columns\TextColumn::make('points_required')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('value')
                    ->money('SAR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('used_count')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ]),

                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        ExchangeReward::TYPE_COUPON => 'Coupon',
                        ExchangeReward::TYPE_GIFT_CARD => 'Gift Card',
                        ExchangeReward::TYPE_SHIPPING_DISCOUNT => 'Shipping Discount',
                        ExchangeReward::TYPE_PRODUCT_DISCOUNT => 'Product Discount',
                        ExchangeReward::TYPE_CASH_CREDIT => 'Cash Credit',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListExchangeRewards::route('/'),
            'create' => Pages\CreateExchangeReward::route('/create'),
            'view' => Pages\ViewExchangeReward::route('/{record}'),
            'edit' => Pages\EditExchangeReward::route('/{record}/edit'),
        ];
    }
}
