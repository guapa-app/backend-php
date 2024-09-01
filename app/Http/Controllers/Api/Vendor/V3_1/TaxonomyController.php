<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\TaxRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\TaxonomyCollection;
use App\Http\Resources\TaxonomyResource;
use Illuminate\Http\Request;

class TaxonomyController extends BaseApiController
{
    private $taxRepository;

    public function __construct(TaxRepositoryInterface $taxRepository)
    {
        parent::__construct();

        $this->taxRepository = $taxRepository;
    }

    public function index(Request $request)
    {
        return TaxonomyCollection::make($this->taxRepository->all($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single($id)
    {
        return TaxonomyResource::make($this->taxRepository->getOneWithRelations($id))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
