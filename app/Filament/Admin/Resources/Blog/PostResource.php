<?php

namespace App\Filament\Admin\Resources\Blog;


use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use App\Models\Country;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\Blog\PostResource\Pages;
use App\Filament\Admin\Resources\Blog\PostResource\RelationManagers;
use App\Enums\PostType;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;


class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Blog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('country_id')
                    ->label('Country')
                    ->required()
                    ->options(Country::query()->pluck('name', 'id'))
                    ->searchable(),

                Forms\Components\Section::make('Images')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                            ->collection('posts')
                            ->multiple()
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
                Forms\Components\Select::make('type')
                    ->options(collect(PostType::cases())->pluck('value', 'value'))
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('admin_id')
                    ->required()
                    ->native(false)
                    ->relationship('admin', 'name'),
                Forms\Components\Select::make('category_id')
                    ->required()
                    ->native(false)
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->title}")
                    ->relationship('category', 'title', function (Builder $query) {
                        return $query->where('type', 'blog_category');
                    }),
                Forms\Components\Select::make('tag_id')
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('title.en')
                            ->label('Title in English')
                            ->required(),
                        Forms\Components\TextInput::make('title.ar')
                            ->label('Title in Arabic')
                            ->required(),
                    ])
                    ->required()
                    ->searchable()
                    ->native(false)
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->title}")
                    ->relationship('tag', 'title'),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('content')
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h1',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options(Post::STATUSES)
                    ->native(false),
                Forms\Components\TextInput::make('youtube_url')
                    ->maxLength(255),

                Forms\Components\Repeater::make('postSocialMedia')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('social_media_id')
                            ->relationship('socialMedia', 'name')
                            ->native(false)
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->required(),
                        Forms\Components\TextInput::make('link')
                            ->label('Link')
                            ->required(),
                    ])
                    ->columnSpanFull()
                    ->columns()
                    ->itemLabel(fn (array $state): ?string => $state['link']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'blog' => 'success',
                        'review' => 'warning',
                        'vote' => 'danger',
                        'question' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('youtube_url')
                    ->limit(30),
                Tables\Columns\SelectColumn::make('status')
                    ->options(Post::STATUSES)
                    ->selectablePlaceholder(false)
                    ->sortable(),
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
                TernaryFilter::make('type')
                    ->placeholder('All Types')
                    ->queries(
                        true: fn (Builder $query) => $query->where('type', 'blog'),
                        false: fn (Builder $query) => $query->whereIn('type', ['review', 'vote', 'question']),
                        blank: fn (Builder $query) => $query,
                    )
                    ->trueLabel('Blog Posts')
                    ->falseLabel('User Posts'),
                SelectFilter::make('specific_type')
                    ->options(collect(PostType::cases())->pluck('value', 'value'))
                    ->label('Post Type'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->persistFilters();
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
