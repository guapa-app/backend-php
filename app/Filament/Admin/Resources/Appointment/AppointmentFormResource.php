<?php

namespace App\Filament\Admin\Resources\Appointment;

use App\Enums\AppointmentTypeEnum;
use App\Filament\Admin\Resources\Appointment;
use App\Filament\Admin\Resources\AppointmentFormResource\Pages;
use App\Models\AppointmentForm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AppointmentFormResource extends Resource
{
    protected static ?string $model = AppointmentForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Appointment';

    protected static ?string $navigationLabel = 'Forms';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options(array_combine(AppointmentTypeEnum::getValues(), AppointmentTypeEnum::options()))
                    ->required()
                    ->reactive()
                    ->native(false)
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        $template = AppointmentTypeEnum::templates()[$state] ?? '';
                        $set('options', $template);
                    }),
                Forms\Components\Textarea::make('options')
                    ->rows(15)
                    ->rules(['nullable', 'json'])
                    ->hint('Options template based on the chosen type. Feel free to customize as needed.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Appointment\AppointmentFormResource\Pages\ListAppointmentForms::route('/'),
            'create' => Appointment\AppointmentFormResource\Pages\CreateAppointmentForm::route('/create'),
            'edit' => Appointment\AppointmentFormResource\Pages\EditAppointmentForm::route('/{record}/edit'),
        ];
    }
}
