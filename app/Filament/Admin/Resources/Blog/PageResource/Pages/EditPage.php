<?php

namespace App\Filament\Admin\Resources\Blog\PageResource\Pages;

use App\Filament\Admin\Resources\Blog\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
