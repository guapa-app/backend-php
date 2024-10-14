<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\AddressController as ApiAddressController;
use App\Http\Requests\AddressListRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Resources\Vendor\V3_1\AddressCollection;
use App\Http\Resources\Vendor\V3_1\AddressResource;
use Illuminate\Http\Request;

/**
 * @group Addresses
 */
class AddressController extends ApiAddressController
{
    public function index(AddressListRequest $request)
    {
        $index = parent::index($request);
        $data = $request->validated();

        $this->checkAddressable($data['addressable_type'], $data['addressable_id']);
        $addresses =$this->addressRepository->all($request);

        return AddressCollection::make($addresses)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single(Request $request)
    {
        $single = parent::single($request);

        return AddressResource::make($single)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function create(AddressRequest $request)
    {
        $item = parent::create($request);

        return AddressResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.created'),
            ]);
    }

    public function update(AddressRequest $request, $id = 0)
    {
        $item = parent::update($request, $id);

        return AddressResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.updated'),
            ]);
    }

    public function delete(int $id = 0)
    {
        parent::delete($id);

        return $this->successJsonRes([], __('api.deleted'));
    }

    private function checkAddressable(string $addressable_type, int $addressable_id, int $requestAddressableId = null): void
    {
        if ($addressable_type === 'user' && $addressable_id !== auth()->id()) {
            abort(403, 'Cannot create address for provided user');
        } elseif ($addressable_type === 'vendor') {
            $vendor = $this->vendorRepository->getOneOrFail($addressable_id);
            if (!$vendor->hasUser(auth()->user())) {
                abort(403, 'You cannot create address for provided vendor');
            }
            // this check for update only.
            if (isset($requestAddressableId) && ($requestAddressableId !== $addressable_id)) {
                abort(403, __('api.not_allowed'));
            }
        }
    }
}
