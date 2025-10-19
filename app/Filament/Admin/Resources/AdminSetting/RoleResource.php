<?php

namespace App\Filament\Admin\Resources\AdminSetting;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\AdminSetting\RoleResource\Pages;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Admin Setting';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Hidden::make('guard_name')
                ->default('admin'),

            Forms\Components\TextInput::make('name')
                ->label(__('name'))
                ->required(),

            Forms\Components\Select::make('permissions')
                ->label(__('permissions'))
                ->preload()
                ->relationship('permissions', 'name', function (Builder $query) {
                    return $query->where('guard_name', 'admin');
                })
                ->getOptionLabelFromRecordUsing(fn(Model $record) => Str::headline($record->name))
                ->multiple()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
