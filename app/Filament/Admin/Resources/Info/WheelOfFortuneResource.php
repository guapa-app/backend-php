<?php

namespace App\Filament\Admin\Resources\Info;

use App\Filament\Admin\Resources\Info;
use App\Models\WheelOfFortune;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WheelOfFortuneResource extends Resource
{
    use Translatable;

    protected static ?string $model = WheelOfFortune::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Info';

    protected static ?string $label = 'Wheel Of Fortune';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('rarity_title')
                    ->required()
                    ->live(onBlur: true)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('probability')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('points')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rarity_title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('probability')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('points')
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
            'index' => Info\WheelOfFortuneResource\Pages\ListWheelOfFortunes::route('/'),
            'create' => Info\WheelOfFortuneResource\Pages\CreateWheelOfFortune::route('/create'),
            'edit' => Info\WheelOfFortuneResource\Pages\EditWheelOfFortune::route('/{record}/edit'),
        ];
    }
}
