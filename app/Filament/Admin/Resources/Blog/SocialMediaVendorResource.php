<?php

namespace App\Filament\Admin\Resources\Blog;

use App\Filament\Admin\Resources\Blog\SocialMediaVendorResource\Pages;
use App\Models\SocialMediaVendor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SocialMediaVendorResource extends Resource
{
    protected static ?string $model = SocialMediaVendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Blog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('social_media_id')
                    ->label(__('Social Media'))
                    ->native(false)
                    ->relationship(name: 'socialMedia')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name}")
                    ->required(),
                Forms\Components\Select::make('vendor_id')
                    ->label(__('Vendor'))
                    ->native(false)
                    ->relationship(name: 'vendor')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name}")
                    ->required(),
                Forms\Components\Textarea::make('link')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('socialMedia.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vendor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('link')
                    ->label('Link')
                    ->url(fn ($record) => $record->link)
                    ->formatStateUsing(fn ($state) => 'View Link')
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListSocialMediaVendors::route('/'),
            'create' => Pages\CreateSocialMediaVendor::route('/create'),
            'edit' => Pages\EditSocialMediaVendor::route('/{record}/edit'),
        ];
    }
}
