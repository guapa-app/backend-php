<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendorClientRequest;
use App\Http\Requests\UpdateVendorClientRequest;
use App\Models\VendorClient;
use App\Services\VendorClientService;

class VendorClientController extends Controller
{
    private ?VendorClientService $vendorClientService;

    public function __construct(VendorClientService $vendorClientService)
    {
        $this->vendorClientService = $vendorClientService;
    }

    public function store(StoreVendorClientRequest $request)
    {
        return $this->vendorClientService->create($request->validated());
    }

    public function update(UpdateVendorClientRequest $request, VendorClient $vendorClient)
    {
        return $this->vendorClientService->update($vendorClient, $request->validated());
    }

    public function destroy(VendorClient $vendorClient)
    {
        return $this->vendorClientService->delete($vendorClient);
    }
}
