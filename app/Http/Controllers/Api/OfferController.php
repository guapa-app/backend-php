<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Contracts\Repositories\OfferRepositoryInterface;
use App\Http\Requests\OfferRequest;
use App\Services\OfferService;

/**
 * @group Offers
 */
class OfferController extends BaseApiController
{
    private $offerRepository;

    private $offerService;

    public function __construct(OfferService $offerService,
        OfferRepositoryInterface $repository)
    {
        parent::__construct();
        
        $this->offerService = $offerService;
        $this->offerRepository = $repository;
    }

    /**
     * Create offer
     * 
     * @responseFile 200 responses/offers/create.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 403 scenario="Cannot create offer for provided product" responses/errors/403.json
     * 
     * @param  \App\Http\Requests\OfferRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(OfferRequest $request)
    {
        // Create the offer
    	$offer = $this->offerService->create($request->validated());
    	return response()->json($offer);
    }

    /**
     * Update offer
     * 
     * @responseFile 200 responses/offers/create.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 403 scenario="Cannot update offer for provided product" responses/errors/403.json
     * 
     * @param  \App\Http\Requests\OfferRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(OfferRequest $request, $id)
    {
        // Update the offer
    	$offer = $this->offerService->update($id, $request->validated());
    	return response()->json($offer);
    }

    /**
     * Delete offer
     * 
     * @responseFile 200 responses/offers/delete.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 403 scenario="Unauthorized to delete offer" responses/errors/403.json
     * 
     * @param  \App\Http\Requests\OfferRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $this->offerRepository->delete($id);
        return response()->json(['id' => $id]);
    }
}
