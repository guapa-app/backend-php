<?php

namespace App\Filament\Admin\Resources\UserVendor\UserProfileResource\Pages;

use App\Filament\Admin\Resources\UserVendor\UserProfileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserProfile extends CreateRecord
{
    protected static string $resource = UserProfileResource::class;
}
