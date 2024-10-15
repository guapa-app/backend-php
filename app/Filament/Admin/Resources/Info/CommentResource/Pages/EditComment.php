<?php

namespace App\Filament\Admin\Resources\Info\CommentResource\Pages;

use App\Filament\Admin\Resources\Info\CommentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComment extends EditRecord
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
