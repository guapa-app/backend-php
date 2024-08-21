<?php

namespace App\Filament\Resources\Info\StaffResource\Pages;

use App\Filament\Resources\Info\StaffResource;
use App\Filament\Resources\Info\UserVendorResource;
use App\Http\Requests\StaffRequest;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserVendor extends EditRecord
{
    protected static string $resource = UserVendorResource::class;

    protected function getFormSchema(): array
    {
        return (new StaffRequest())
            ->rules([
                'vendor_id' => ['required', 'in:' . UserVendorResource::getCurrentUserVendorId()],
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
