<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\OfferRepositoryInterface;
use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Services\OfferService;
use Illuminate\Http\Request;

/**
 * @group Offers
 */
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

    public function index(Request $request)
    {
        return $this->offerRepository->all($request);
    }

    /**
     * Create offer.
     *
     * @responseFile 200 responses/offers/create.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 403 scenario="Cannot create offer for provided product" responses/errors/403.json
     *
     * @param OfferRequest $request
     * @return Offer
     */
    public function create(OfferRequest $request)
    {
        // Create the offer
        return $this->offerService->create($request->validated());
    }

    /**
     * Update offer.
     *
     * @responseFile 200 responses/offers/create.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 403 scenario="Cannot update offer for provided product" responses/errors/403.json
     *
     * @param OfferRequest $request
     * @return Offer
     */
    public function update(OfferRequest $request, $id)
    {
        // Update the offer
        return $this->offerService->update($id, $request->validated());
    }

    /**
     * Delete offer.
     *
     * @responseFile 200 responses/offers/delete.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 403 scenario="Unauthorized to delete offer" responses/errors/403.json
     *
     * @param $id
     * @return array|int[]|null
     */
    public function delete($id)
    {
        return $this->offerService->delete($id);
    }
}
