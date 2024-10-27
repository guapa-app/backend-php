<?php

namespace App\Filament\Admin\Resources\UserVendor\UserResource\Pages;

use App\Filament\Admin\Resources\UserVendor\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
