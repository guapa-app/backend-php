<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\OfferRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\OfferRequest;
use App\Http\Resources\Vendor\V3_1\OfferResource;
use App\Http\Resources\Vendor\V3_1\ProductResource;
use App\Services\OfferService;
use Illuminate\Http\Request;

class OfferController extends BaseApiController
{
    private $offerRepository;

    private $offerService;

    public function __construct(
        OfferService $offerService,
        OfferRepositoryInterface $repository
    ) {
        parent::__construct();

        $this->offerService = $offerService;
        $this->offerRepository = $repository;
    }

    /**
     * This API for vendors ONLY.
     * To display all products that has offer
     * even offers expired, active or incoming.
     *
     * @param  Request  $request
     * @return ProductResource|object
     */
    public function index(Request $request)
    {
        $request->merge(['vendor_id' => $this->user->managerVendorId()]);
        $offers = $this->offerRepository->all($request);
        return ProductResource::collection($offers)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function create(OfferRequest $request)
    {
        $offer = $this->offerService->create($request->validated());
        return OfferResource::make($offer)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function update(OfferRequest $request, $id)
    {
        $offer = $this->offerService->update($id, $request->validated());
        return OfferResource::make($offer)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function delete($id)
    {
        $this->offerService->delete($id);
        return $this->successJsonRes([], __('api.success'));
    }
}
