<?php

namespace App\Filament\Admin\Resources\Info;

use Filament\Forms;
use Filament\Tables;
use App\Models\Country;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Admin\Resources\Info\CountryResource\Pages\EditCountry;
use App\Filament\Admin\Resources\Info\CountryResource\Pages\CreateCountry;
use App\Filament\Admin\Resources\Info\CountryResource\Pages\ListCountries;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-asia-australia';

    protected static ?string $navigationGroup = 'Info';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Country Name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('currency_code')
                    ->label('Currency Code')
                    ->required()
                    ->maxLength(3),
                Forms\Components\TextInput::make('phone_code')
                    ->label('Phone Code')
                    ->required()
                    ->maxLength(5),
                Forms\Components\TextInput::make('phone_length')
                    ->label('Phone Length')
                    ->numeric()
                    ->required()
                    ->maxValue(16)
                    ->minValue(8),
                Forms\Components\TextInput::make('tax_percentage')
                    ->label('Tax Percentage')
                    ->numeric()
                    ->step(0.01) // Allow decimal values
                    ->placeholder('e.g., 15.00')
                    ->required()
                    ->maxValue(100)
                    ->minValue(0),
                Forms\Components\Toggle::make('active')
                    ->label('Active')
                    ->default(true),
                Forms\Components\FileUpload::make('icon')
                    ->label('Icon')
                    ->image()
                    ->directory('countries/icons')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('Country Name')->searchable(),
                TextColumn::make('currency_code')->label('Currency Code'),
                TextColumn::make('phone_code')->label('Phone Code'),
                TextColumn::make('tax_percentage')
                    ->label('Tax Percentage')
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state . '%'),
                IconColumn::make('active')->label('Active')
                    ->boolean(),
                ImageColumn::make('icon')->label('Icon')->square(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime(),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define relationships here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCountries::route('/'),
            'create' => CreateCountry::route('/create'),
            'edit' => EditCountry::route('/{record}/edit'),
        ];
    }
}
