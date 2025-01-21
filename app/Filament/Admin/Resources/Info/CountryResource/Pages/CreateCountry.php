<?php

namespace App\Filament\Admin\Resources\Info\CountryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\Info\CountryResource;

class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;
}
