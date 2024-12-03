<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Resources\CountryResource;
use App\Http\Controllers\Api\BaseApiController;

class CountryController extends BaseApiController
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $countries = Country::all();

        return CountryResource::collection($countries)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
