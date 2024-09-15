<?php

namespace App\Filament\Resources\Info;

use App\Filament\Resources\Info;
use App\Models\Vendor;
use App\Traits\FilamentVendorAccess;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VendorResource extends Resource
{
    use FilamentVendorAccess;

    protected static ?string $model = Vendor::class;

    protected static ?string $navigationGroup = 'Info';

    protected static ?string $modelLabel = 'Doctors';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function canViewAny(): bool
    {
        return (bool) auth()->user()?->Vendor?->isParent();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->SubVendors(parent: self::authVendorId());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(150),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->rules(['required', 'unique:vendors,email', 'unique:users,email'])
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->rules(['required', 'unique:vendors,phone', 'unique:users,phone'])
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options(Vendor::TYPES),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options(Vendor::STATUSES),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options(Vendor::STATUSES),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Info\VendorResource\Pages\ListVendors::route('/'),
            'create' => Info\VendorResource\Pages\CreateVendor::route('/create'),
            'edit' => Info\VendorResource\Pages\EditVendor::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return (bool) auth()->user()?->Vendor?->isParent();
    }
}
