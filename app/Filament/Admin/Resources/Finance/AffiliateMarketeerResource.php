<?php

namespace App\Filament\Admin\Resources\Finance;

use App\Filament\Admin\Resources\Finance\AffiliateMarketeerResource\Pages;
use App\Filament\Admin\Resources\Finance\AffiliateMarketeerResource\RelationManagers;
use App\Models\Finance\AffiliateMarketeer;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AffiliateMarketeerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Finance';

    public static function getLabel(): string
    {
        return 'Affiliate Marketeer';
    }

    public static function getPluralLabel(): string
    {
        return 'Affiliate Marketeers';
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', 'affiliate_marketeer');
            })
            ->withCount('coupons');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('coupons_count')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAffiliateMarketeers::route('/'),
            // 'create' => Pages\CreateAffiliateMarketeer::route('/create'),
            // 'edit' => Pages\EditAffiliateMarketeer::route('/{record}/edit'),
        ];
    }
}
