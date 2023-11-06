<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Http\Requests\VendorRequest;
use App\Services\VendorService;
use Illuminate\Http\Request;

class VendorController extends BaseAdminController
{
    private $vendorService;
    private $vendorRepository;

    public function __construct(VendorService $vendorService, VendorRepositoryInterface $vendorRepository)
    {
        parent::__construct();

        $this->vendorService = $vendorService;
        $this->vendorRepository = $vendorRepository;
    }

    public function index(Request $request)
    {
        $vendors = $this->vendorRepository->all($request);

        return response()->json($vendors);
    }

    public function single($id = 0)
    {
        $vendor = $this->vendorRepository->getOneWithRelations($id);

        return response()->json($vendor);
    }

    public function create(VendorRequest $request)
    {
        $data = $request->validated();
        $vendor = $this->vendorService->create($data);

        return response()->json($vendor);
    }

    public function update(VendorRequest $request, $id = 0)
    {
        $vendor = $this->vendorService->update($id, $request->validated());

        return response()->json($vendor);
    }

    public function delete($id = 0)
    {
        $ids = $this->vendorRepository->delete($id);

        return response()->json($ids);
    }
}
