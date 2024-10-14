<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ShareLinkResource\Pages;
use App\Models\ShareLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShareLinkResource extends Resource
{
    protected static ?string $model = ShareLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('shareable_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('shareable_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('identifier')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('link')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('shareable_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shareable_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('identifier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('link')
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
            'index' => Pages\ListShareLinks::route('/'),
            'create' => Pages\CreateShareLink::route('/create'),
            'edit' => Pages\EditShareLink::route('/{record}/edit'),
        ];
    }
}
