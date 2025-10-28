<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\V3_1\DiseaseRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\V3_1\DiseaseCollection;
use Illuminate\Http\Request;

class DiseaseController extends BaseApiController
{
    protected $diseaseRepository;

    public function __construct(DiseaseRepositoryInterface $diseaseRepository)
    {
        parent::__construct();

        $this->diseaseRepository = $diseaseRepository;
    }

    public function index(Request $request)
    {
        $diseases = $this->diseaseRepository->all($request);
        return DiseaseCollection::make($diseases)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
