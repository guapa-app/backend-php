<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\OfferRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\User\V3_1\ProductCollection;
use Illuminate\Http\Request;

class OfferController extends BaseApiController
{
    private $offerRepository;

    public function __construct(OfferRepositoryInterface $repository) {
        parent::__construct();
        $this->offerRepository = $repository;
    }

    /**
     * This API for vendors ONLY.
     * To display all products that has offer
     *
     * @param  Request  $request
     * @return ProductCollection
     */
    public function index(Request $request)
    {
        $offers = $this->offerRepository->all($request);

        return ProductCollection::make($offers)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
