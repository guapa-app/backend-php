<?php

namespace App\Filament\Admin\Resources\AdminSetting;

use App\Filament\Admin\Resources\AdminSetting\NotificationSettingResource\Pages;
use App\Models\NotificationSetting;
use App\Models\Admin;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use App\Enums\NotificationTypeEnum;

class NotificationSettingResource extends Resource
{
    protected static ?string $model = NotificationSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?string $navigationGroup = 'Admin Setting';
    protected static ?string $label = 'Notification Settings';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();
        if ($user instanceof \App\Models\Admin && $user->isSuperAdmin()) {
            // Super admin sees all settings
            return parent::getEloquentQuery();
        }
        // Normal admin: see their own settings AND global settings
        return parent::getEloquentQuery()->where(function ($query) use ($user) {
            $query->where('admin_id', $user->id)
                ->orWhereNull('admin_id');
        });
    }

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $isSuperAdmin = $user instanceof \App\Models\Admin && $user->isSuperAdmin();
        return $form
            ->schema([
                Forms\Components\Select::make('notification_module')
                    ->label('Module')
                    ->options(collect(NotificationTypeEnum::cases())->mapWithKeys(fn($case) => [$case->value => ucfirst(str_replace('-', ' ', $case->value))]))
                    ->required()
                    ->default(NotificationTypeEnum::Order->value),
                Forms\Components\Select::make('admin_id')
                    ->label('Admin')
                    ->options(Admin::all()->pluck('name', 'id'))
                    ->nullable()
                    ->default($isSuperAdmin ? null : $user?->id)
                    ->visible(fn() => $isSuperAdmin)
                    ->disabled(fn() => !$isSuperAdmin),
                Forms\Components\Select::make('channels')
                    ->label('Channel')
                    ->options([
                        'in_app' => 'In-App',
                        'firebase' => 'Firebase',
                        'whatsapp' => 'WhatsApp',
                        'mail' => 'Mail',
                        'sms' => 'SMS',
                    ])
                    ->required()
                    ->default('in_app'),
                Forms\Components\Toggle::make('created_by_super_admin')
                    ->label('Created by Super Admin')
                    ->default($isSuperAdmin)
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('notification_module')->searchable(),
                Tables\Columns\TextColumn::make('admin.name')->label('Admin')->searchable(),
                Tables\Columns\TextColumn::make('channels')->formatStateUsing(fn($state) => ucfirst($state)),
                Tables\Columns\BooleanColumn::make('created_by_super_admin'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotificationSettings::route('/'),
            'create' => Pages\CreateNotificationSetting::route('/create'),
            'edit' => Pages\EditNotificationSetting::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user instanceof \App\Models\Admin && $user->canManageNotificationSettings();
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return $user instanceof \App\Models\Admin && $user->canManageNotificationSettings();
    }

    public static function canEdit(
        \Illuminate\Database\Eloquent\Model $record
    ): bool {
        $user = Auth::user();
        return $user instanceof \App\Models\Admin && $user->canManageNotificationSettings();
    }

    public static function canDelete(
        \Illuminate\Database\Eloquent\Model $record
    ): bool {
        $user = Auth::user();
        return $user instanceof \App\Models\Admin && $user->canManageNotificationSettings();
    }
}
