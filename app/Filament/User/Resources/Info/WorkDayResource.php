<?php

namespace App\Filament\User\Resources\Info;

use App\Enums\WorkDay as EnumsWorkDay;
use App\Filament\User\Resources\Info\WorkDayResource\Pages;
use App\Models\WorkDay;
use App\Traits\FilamentVendorAccess;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkDayResource extends Resource
{
    use FilamentVendorAccess;

    protected static ?string $model = WorkDay::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Info';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('day')
                    ->required()
                    ->options(EnumsWorkDay::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('day'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWorkDays::route('/'),
            'create' => Pages\CreateWorkDay::route('/create'),
            'edit'   => Pages\EditWorkDay::route('/{record}/edit'),
        ];
    }
}
