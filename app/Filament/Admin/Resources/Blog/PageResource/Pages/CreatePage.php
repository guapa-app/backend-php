<?php

namespace App\Filament\Admin\Resources\Blog\PageResource\Pages;

use App\Filament\Admin\Resources\Blog\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            // ...
        ];
    }
}
