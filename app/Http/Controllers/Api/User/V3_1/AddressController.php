<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\AddressRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\User\AddressListRequest;
use App\Http\Requests\V3_1\User\AddressRequest;
use App\Http\Resources\User\V3_1\AddressCollection;
use App\Http\Resources\User\V3_1\AddressResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @group Addresses
 */
class AddressController extends BaseApiController
{
    private $addressRepository;
    private $vendorRepository;

    public function __construct(AddressRepositoryInterface $addressRepository, VendorRepositoryInterface $vendorRepository)
    {
        parent::__construct();

        $this->addressRepository = $addressRepository;
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * Address list.
     *
     * @queryParam addressable_id integer required Addressable entity id. Example: 3
     * @queryParam addressable_type string required Addressable entity type (vendor, user). Example: vendor
     *
     * @responseFile 200 responses/addresses/list.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 403 scenario="Unauthorized to view addresses for provided entity" responses/errors/403.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     *
     * @param AddressListRequest $request
     * @return Collection|LengthAwarePaginator|object
     */
    public function index(AddressListRequest $request)
    {
        $data = $request->validated();

        $request->merge($data);

        $address =  $this->addressRepository->all($request);

        return AddressCollection::make($address)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    /**
     * @param Request $request
     * @return Model|null
     */
    public function single(Request $request)
    {
        $address = $this->addressRepository->getOneOrFail($request->id);

        return AddressResource::make($address)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);

    }

    /**
     * Create Address.
     *
     * @responseFile 200 responses/addresses/create.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 403 scenario="Cannot create address for provided entity" responses/errors/403.json
     *
     * @param AddressRequest $request
     *
     * @return Model
     */
    public function create(AddressRequest $request)
    {
        $data = $request->validated();

        $address =  $this->addressRepository->create($data);
        return AddressResource::make($address)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    /**
     * Update Address.
     *
     * @responseFile 200 responses/addresses/create.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 404 scenario="Not found" responses/errors/404.json
     * @responseFile 403 scenario="Not authorized to update address" responses/errors/403.json
     *
     * @urlParam id integer required Address id. Example: 3
     *
     * @param AddressRequest $request
     * @param int $id
     *
     * @return Model
     */
    public function update(AddressRequest $request, $id = 0)
    {
        $address = $this->addressRepository->getOneOrFail($id);

        $address = $this->addressRepository->update($id, $request->validated());
        return AddressResource::make($address)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    /**
     * Delete Address.
     *
     * @responseFile 200 responses/Address/delete.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 404 scenario="Not found" responses/errors/404.json
     * @responseFile 403 scenario="Not authorized to delete address" responses/errors/403.json
     *
     * @param int $id
     *
     * @return array
     */
    public function delete(int $id = 0)
    {
        $address = $this->addressRepository->getOneOrFail($id);

        $this->checkAddressable($address->addressable_type, $address->addressable_id);

        $ids = $this->addressRepository->delete($id);

        return $this->successJsonRes([], __('api.deleted'));
    }

    /**
     * @param string $addressable_type
     * @param int $addressable_id
     * @return void
     */
    private function checkAddressable(string $addressable_type, int $addressable_id): void
    {
        if ($addressable_type === 'user' && $addressable_id !== auth()->id()) {
            abort(403, 'Cannot create address for provided user');
        } elseif ($addressable_type === 'vendor') {
            $vendor = $this->vendorRepository->getOneOrFail($addressable_id);
            if (!$vendor->hasUser(auth()->user())) {
                abort(403, 'You cannot create address for provided vendor');
            }
        }
    }
}
