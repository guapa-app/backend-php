<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\AddressRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\Vendor\AddressListRequest;
use App\Http\Requests\V3_1\Vendor\AddressRequest;
use App\Http\Resources\Vendor\V3_1\AddressCollection;
use App\Http\Resources\Vendor\V3_1\AddressResource;
use Illuminate\Http\Request;

/**
 * @group Addresses
 */
class AddressController extends BaseApiController
{
    private $addressRepository;

    public function __construct(AddressRepositoryInterface $addressRepository )
    {
        parent::__construct();

        $this->addressRepository = $addressRepository;
    }
    public function index(AddressListRequest $request)
    {
        $vendorId = $this->user->vendor->id;
        $request->merge(['addressable_id' => $vendorId, 'addressable_type' => 'vendor']);

        $addresses =$this->addressRepository->all($request);

        return AddressCollection::make($addresses)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single(Request $request)
    {
        $address = $this->addressRepository->getOneOrFail($request->id);

        return AddressResource::make($address)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function create(AddressRequest $request)
    {
        $data = $this->prepareAddressData($request->validated());
        $data['country_id'] = auth()->user()->country_id;

        $item = $this->addressRepository->create($data);

        return AddressResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.created'),
            ]);
    }

    public function update(AddressRequest $request, $id = 0)
    {
        $data = $this->prepareAddressData($request->validated());
        $address = $this->addressRepository->getOneOrFail($id);

        if ($address->addressable_id !== $data['addressable_id']) {
            return $this->errorJsonRes([], __('api.not_allowed'), 403);
        }

        $item =  $this->addressRepository->update($id, $data);
        return AddressResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.updated'),
            ]);
    }

    public function delete(int $id = 0)
    {
        $address = $this->addressRepository->getOneOrFail($id);

        if ($address->addressable_id !== $this->user->vendor->id) {
            return $this->errorJsonRes([], __('api.not_allowed'), 403);
        }

        $this->addressRepository->delete($id);

        return $this->successJsonRes([], __('api.deleted'));
    }

    /**
     * Prepare address data for creation or update
     *
     * @param array $data
     * @return array
     */
    private function prepareAddressData(array $data): array
    {
        return array_merge($data, [
            'addressable_type' => 'vendor',
            'addressable_id' => $this->user->vendor->id,
        ]);
    }
}
