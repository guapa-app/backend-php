<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Contracts\Repositories\OfferRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\OfferRequest;
use App\Services\OfferService;

class OfferController extends BaseAdminController
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

    public function index(Request $request)
    {
        $offers = $this->offerRepository->all($request);
        return response()->json($offers);
    }

    public function single($id)
    {
    	$offer = $this->offerRepository->getOneWithRelations($id);
    	return response()->json($offer);
    }

    public function create(OfferRequest $request)
    {
        // Create the offer
    	$offer = $this->offerService->create($request->validated());
    	return response()->json($offer);
    }

    public function update(OfferRequest $request, $id)
    {
        // Update the offer
    	$offer = $this->offerService->update($id, $request->validated());
    	return response()->json($offer);
    }

    public function delete($id)
    {
        $this->offerRepository->delete($id);
        return response()->json(['id' => $id]);
    }
}
