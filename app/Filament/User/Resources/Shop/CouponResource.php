<?php

namespace App\Filament\User\Resources\Shop;

use App\Filament\User\Resources\Shop\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('discount_percentage')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%'),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->required(),
                Forms\Components\TextInput::make('max_uses')
                    ->numeric()
                    ->nullable(),
                Forms\Components\TextInput::make('single_user_usage')
                    ->numeric()
                    ->nullable(),
                Forms\Components\MultiSelect::make('products')
                    ->relationship('products', 'title', function (Builder $query) {
                        // Assuming you have a method to get the current vendor
                        $vendorId = auth()->user()->userVendors->first()->vendor_id;
                        return $query->where('vendor_id', $vendorId);
                    })
                    ->preload() // Preload the options for better performance
                    ->searchable(), // Add searchability for easier selection
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('discount_percentage'),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('max_uses'),
                Tables\Columns\TextColumn::make('single_user_usage'),
                TagsColumn::make('products.title')
                    ->label('Applicable Products')
                    ->limit(3)
                    ->searchable(),
                Tables\Columns\TextColumn::make('discount_source')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
        ];
    }
}
