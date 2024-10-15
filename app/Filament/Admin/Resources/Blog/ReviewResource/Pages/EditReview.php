<?php

namespace App\Filament\Admin\Resources\Blog\ReviewResource\Pages;

use App\Filament\Admin\Resources\Blog\ReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReview extends EditRecord
{
    protected static string $resource = ReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
