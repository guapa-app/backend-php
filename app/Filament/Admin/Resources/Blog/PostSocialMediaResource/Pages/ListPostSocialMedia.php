<?php

namespace App\Filament\Admin\Resources\Blog\PostSocialMediaResource\Pages;

use App\Filament\Admin\Resources\Blog\PostSocialMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPostSocialMedia extends ListRecords
{
    protected static string $resource = PostSocialMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
