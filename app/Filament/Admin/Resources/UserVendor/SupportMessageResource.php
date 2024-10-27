<?php

namespace App\Filament\Admin\Resources\UserVendor;

use App\Enums\SupportMessageSenderType;
use App\Enums\SupportMessageStatus;
use App\Filament\Admin\Resources\UserVendor\SupportMessageResource\Actions\ReplyToTicketAction;
use App\Filament\Admin\Resources\UserVendor\SupportMessageResource\Pages;
use App\Models\SupportMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SupportMessageResource extends Resource
{
    protected static ?string $model = SupportMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'User & Vendor';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('parent_id')
                    ->native(false)
                    ->relationship('parent', 'subject'),
                Forms\Components\Select::make('sender_type')
                    ->options(SupportMessageSenderType::toSelect())
                    ->native(false),
                Forms\Components\Select::make('support_message_type_id')
                    ->native(false)
                    ->relationship('supportMessageType', 'name'),
                Forms\Components\Select::make('user_id')
                    ->native(false)
                    ->relationship('user', 'name'),
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Select::make('status')
                    ->options(SupportMessageStatus::toSelect())
                    ->default(SupportMessageStatus::Pending)
                    ->native(false),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(30),
                Forms\Components\DateTimePicker::make('read_at'),
                Forms\Components\Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereNull('parent_id');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('support_message_type.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
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
                ReplyToTicketAction::make('Reply'),
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
            'index' => Pages\ListSupportMessages::route('/'),
            'create' => Pages\CreateSupportMessage::route('/create'),
            'edit' => Pages\EditSupportMessage::route('/{record}/edit'),
        ];
    }
}
