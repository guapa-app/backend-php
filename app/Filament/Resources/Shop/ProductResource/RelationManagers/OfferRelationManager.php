<?php

namespace App\Filament\Resources\Shop\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OfferRelationManager extends RelationManager
{
    protected static string $relationship = 'offer';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                TextEntry::make('title'),
                TextEntry::make('discount')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                TextEntry::make('description'),
                TextEntry::make('terms'),
                TextEntry::make('starts_at'),
                TextEntry::make('expires_at'),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_id')
                    ->hidden()
                    ->default($this->ownerRecord->id),
                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\TextInput::make('discount')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100)
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('terms')
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('starts_at')
                    ->required(),
                Forms\Components\DatePicker::make('expires_at')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('price')
                    ->numeric(),
                Tables\Columns\TextColumn::make('discount')
                    ->formatStateUsing(fn ($state): string => $state . '%'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('starts_at')
                    ->date(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->date(),
            ])
            ->paginated(false)
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->createAnother(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public function canCreate(): bool
    {
        return !$this->getAllTableRecordsCount();
    }

    public function getPages(): array
    {
        return [
            'index' => Pages\ListOffers::route('/'),
            'create' => Pages\CreateOffer::route('/create'),
            'edit' => Pages\EditOffer::route('/{record}/edit'),
        ];
    }
}
