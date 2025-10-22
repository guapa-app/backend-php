<?php

namespace App\Filament\Admin\Resources\UserVendor\UserResource\Pages;

use App\Filament\Admin\Resources\UserVendor\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        return $data;
    }
}
