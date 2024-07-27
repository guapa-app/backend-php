<?php

namespace App\Services;

use App\Contracts\Repositories\VendorClientRepositoryInterface;
use App\Models\VendorClient;
use Illuminate\Database\Eloquent\Model;

class VendorClientService
{
    private ?VendorClientRepositoryInterface $vendorClientRepository;
    public function __construct(VendorClientRepositoryInterface $vendorClientRepository)
    {
        $this->vendorClientRepository = $vendorClientRepository;
    }

    public function create(array $data): VendorClient
    {
        $data['vendor_id'] = auth()->user()?->userVendors->first()?->vendor_id;
        return $this->vendorClientRepository->create($data);
    }

    public function update(VendorClient $vendorClient, array $data): Model
    {
        return $this->vendorClientRepository->update($vendorClient, $data);
    }

    public function delete(VendorClient $vendorClient): ?array
    {
        return $this->vendorClientRepository->delete($vendorClient->id);
    }
}
