<?php

namespace App\Filament\Admin\Resources\Blog\PostResource\Pages;

use App\Enums\PostType;
use App\Filament\Admin\Resources\Blog\PostResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Blog' => Tab::make()->query(fn ($query) => $query->where('type', PostType::Blog->value)),
            'Community' => Tab::make()->query(fn ($query) => $query->whereIn('type', PostType::availableForCreateByUser())),
        ];
    }
}
