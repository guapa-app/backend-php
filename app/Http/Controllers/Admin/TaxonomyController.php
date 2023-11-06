<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\TaxRepositoryInterface;
use App\Http\Requests\TaxonomyRequest;
use App\Services\TaxService;
use Illuminate\Http\Request;

class TaxonomyController extends BaseAdminController
{
    private $taxRepository;
    private $taxService;

    public function __construct(
        TaxRepositoryInterface $taxRepository,
        TaxService $taxService
    ) {
        parent::__construct();

        $this->taxRepository = $taxRepository;
        $this->taxService = $taxService;
    }

    public function index(Request $request)
    {
        $taxes = $this->taxRepository->all($request);

        return response()->json($taxes);
    }

    public function single($id)
    {
        $taxonomy = $this->taxRepository->getOneWithRelations($id);

        return response()->json($taxonomy);
    }

    public function create(TaxonomyRequest $request)
    {
        // Create the taxonomy
        $taxonomy = $this->taxService->create($request->validated());

        return response()->json($taxonomy);
    }

    public function update(TaxonomyRequest $request, $id)
    {
        // Update the taxonomy
        $taxonomy = $this->taxService->update($id, $request->validated());

        // If the taxonomy is a category and
        return response()->json($taxonomy);
    }

    public function delete($id)
    {
        $this->taxService->delete($id);

        return response()->json(['id' => $id]);
    }
}
