<?php

namespace App\Filament\Admin\Resources\Info\CommentResource\Pages;

use App\Filament\Admin\Resources\Info\CommentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComments extends ListRecords
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
