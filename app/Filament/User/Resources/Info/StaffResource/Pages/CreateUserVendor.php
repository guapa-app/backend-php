<?php

namespace App\Filament\User\Resources\Info\StaffResource\Pages;

use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Filament\User\Resources\Info\StaffResource;
use App\Filament\User\Resources\Info\UserVendorResource;
use App\Services\VendorService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateUserVendor extends CreateRecord
{
    protected static string $resource = UserVendorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user']['password'] = Hash::make($data['user']['password']);
        $data['vendor_id'] = UserVendorResource::getCurrentUserVendorId();

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data['vendor_id'] = UserVendorResource::getCurrentUserVendorId();
        $user = app(VendorService::class)->addStaff(app(VendorRepositoryInterface::class)->getOneOrFail($data['vendor_id']), $data['user']);

        return $user;
    }
}
