<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Contracts\Repositories\AddressRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;

/**
 * @group Addresses
 */
class AddressController extends BaseApiController
{
    private $addressRepository;
    private $vendorRepository;

    public function __construct(AddressRepositoryInterface $addressRepository,
        VendorRepositoryInterface $vendorRepository)
    {
        parent::__construct();
        
        $this->addressRepository = $addressRepository;
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * Address list
     *
     * @queryParam addressable_id integer required Addressable entity id. Example: 3
     * @queryParam addressable_type string required Addressable entity type (vendor, user). Example: vendor
     * 
     * @responseFile 200 responses/addresses/list.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 403 scenario="Unauthorized to view addresses for provided entity" responses/errors/403.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function index(Request $request)
	{
        $data = $this->validate($request, [
            'addressable_id' => 'required|integer',
            'addressable_type' => 'required|string',
        ]);

        if ($data['addressable_type'] === 'user' && ((int)$data['addressable_id']) !== auth()->id()) {
            abort(403, 'Cannot create address for provided user');
        } elseif ($data['addressable_type'] === 'vendor') {
            $vendor = $this->vendorRepository->getOneOrFail($data['addressable_id']);
            if ( ! $vendor->hasUser(auth()->user())) {
                abort(403, 'You cannot create address for provided vendor');
            }
        }

        $request->merge($data);

        $addresses = $this->addressRepository->all($request);
        return response()->json($addresses);
	}

    /**
     * Create Address
     * 
     * @responseFile 200 responses/addresses/create.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 403 scenario="Cannot create address for provided entity" responses/errors/403.json
     * 
     * @param  \App\Http\Requests\AddressRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
	public function create(AddressRequest $request)
	{
        $data = $request->validated();
        
        if ($data['addressable_type'] === 'user' && ((int)$data['addressable_id']) !== auth()->id()) {
            abort(403, 'Cannot create address for provided user');
        } elseif ($data['addressable_type'] === 'vendor') {
            $vendor = $this->vendorRepository->getOneOrFail($data['addressable_id']);
            if ( ! $vendor->hasUser(auth()->user())) {
                abort(403, 'You cannot create address for provided vendor');
            }
        }

        $address = $this->addressRepository->create($data);
        return response()->json($address);
	}

    /**
     * Update Address
     *
     * @responseFile 200 responses/addresses/create.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 404 scenario="Not found" responses/errors/404.json
     * @responseFile 403 scenario="Not authorized to update address" responses/errors/403.json
     *
     * @urlParam id integer required Address id. Example: 3
     * 
     * @param  \App\Http\Requests\AddressRequest $request
     * @param  integer $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AddressRequest $request, $id = 0)
    {
        $address = $this->addressRepository->getOneOrFail($id);

        if ($address->addressable_type === 'user' && $data['addressable_id'] !== auth()->id()) {
            abort(403, 'Cannot update this address');
        } elseif ($address->addressable_type === 'vendor') {
            $vendor = $this->vendorRepository->getOneOrFail($address->addressable_id);
            if ( ! $vendor->hasUser(auth()->user())) {
                abort(403, 'You cannot create address for provided vendor');
            }
        }

        $address = $this->addressRepository->update($id, $request->validated());

        return response()->json($address);
    }

    /**
     * Delete Address
     *
     * @responseFile 200 responses/Address/delete.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 404 scenario="Not found" responses/errors/404.json
     * @responseFile 403 scenario="Not authorized to delete address" responses/errors/403.json
     * 
     * @param  integer $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id = 0)
    {
        $id = (int) $id;

        $address = $this->addressRepository->getOneOrFail($id);

        if ($address->addressable_type === 'user' && $data['addressable_id'] !== auth()->id()) {
            abort(403, 'Cannot delete this address');
        } elseif ($address->addressable_type === 'vendor') {
            $vendor = $this->vendorRepository->getOneOrFail($address->addressable_id);
            if ( ! $vendor->hasUser(auth()->user())) {
                abort(403, 'You cannot delete this address');
            }
        }


        $ids = $this->addressRepository->delete($id);

        return response()->json($ids);
    }
}
