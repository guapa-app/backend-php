<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\CityRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\V3\CityCollection;
use Illuminate\Http\Request;

class CityController extends BaseApiController
{
    private $cityRepository;

    public function __construct(CityRepositoryInterface $cityRepository)
    {
        parent::__construct();

        $this->cityRepository = $cityRepository;
    }

    public function index(Request $request)
    {
        $records = $this->cityRepository->all($request);

        return CityCollection::make($records)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
