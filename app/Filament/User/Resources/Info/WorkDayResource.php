<?php

namespace App\Filament\User\Resources\Info;

use Filament\Forms;
use Filament\Tables;
use App\Models\WorkDay;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\FilamentVendorAccess;
use App\Enums\WorkDay as EnumsWorkDay;
use App\Filament\User\Resources\Info\WorkDayResource\Pages;

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
                    // hours srat and end
                    Forms\Components\TimePicker::make('start_time')
                    ->required()
                    ->label('Start Time'),
                Forms\Components\TimePicker::make('end_time')
                    ->required()
                    ->label('End Time'),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options(['online' => 'Online', 'offline' => 'Offline'])
                    ->label('Type')
                    ->default('offline'),
                Forms\Components\Select::make(name: 'is_active')
                    ->required()
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->label('Status'),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('day'),
                Tables\Columns\TextColumn::make('start_time')
                ->label('Start Time'),
            Tables\Columns\TextColumn::make('end_time')
                ->label('End Time'),
            Tables\Columns\TextColumn::make('type')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'online' => 'success',
                    'offline' => 'info',
                    default => 'gray',
                }),
            Tables\Columns\TextColumn::make('is_active')
                ->label('Status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    '1' => 'success',
                    '0' => 'danger',
                    default => 'gray',
                }),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
