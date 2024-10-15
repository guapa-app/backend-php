<?php

namespace App\Filament\Admin\Resources\Blog;

use App\Filament\Admin\Resources\Blog\PostSocialMediaResource\Pages;
use App\Models\PostSocialMedia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostSocialMediaResource extends Resource
{
    protected static ?string $model = PostSocialMedia::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Blog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('social_media_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('link')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('post_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('social_media_id')
                    ->numeric()
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
            'index' => Pages\ListPostSocialMedia::route('/'),
            'create' => Pages\CreatePostSocialMedia::route('/create'),
            'edit' => Pages\EditPostSocialMedia::route('/{record}/edit'),
        ];
    }
}
