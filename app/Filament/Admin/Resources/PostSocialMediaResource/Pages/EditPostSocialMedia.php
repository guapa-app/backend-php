<?php

namespace App\Filament\Admin\Resources\PostSocialMediaResource\Pages;

use App\Filament\Admin\Resources\PostSocialMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPostSocialMedia extends EditRecord
{
    protected static string $resource = PostSocialMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
