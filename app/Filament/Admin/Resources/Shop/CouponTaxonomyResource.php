<?php

namespace App\Filament\Admin\Resources\Shop;

use App\Filament\Admin\Resources\Shop\CouponTaxonomyResource\Pages;
use App\Models\CouponTaxonomy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CouponTaxonomyResource extends Resource
{
    protected static ?string $model = CouponTaxonomy::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('coupon_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('taxonomy_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('coupon_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('taxonomy_id')
                    ->numeric()
                    ->sortable(),
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
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCouponTaxonomies::route('/'),
            'create' => Pages\CreateCouponTaxonomy::route('/create'),
            'edit' => Pages\EditCouponTaxonomy::route('/{record}/edit'),
        ];
    }
}
