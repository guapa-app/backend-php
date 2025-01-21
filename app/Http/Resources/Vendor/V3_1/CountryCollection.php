<?php

namespace App\Http\Resources\Vendor\V3_1;

use App\Http\Resources\CountryResource;

class CountryCollection extends GeneralCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = CountryResource::class;

}
