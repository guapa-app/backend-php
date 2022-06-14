<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Contracts\Repositories\AddressRepositoryInterface;
use Illuminate\Http\Request;

class AddressController extends Controller
{
	private $addressRepository;

    public function __construct(AddressRepositoryInterface $addressRepository)
    {
    	$this->addressRepository = $addressRepository;
    }

    public function index(Request $request)
    {
    	$cities = $this->addressRepository->all($request);
    	return response()->json($cities);
    }

    public function single($id)
    {
    	$address = $this->addressRepository->getOneWithRelations($id);
    	return response()->json($address);
    }

    public function create(AddressRequest $request)
    {
    	$data = $request->validated();
    	$address = $this->addressRepository->create($data);
    	return response()->json($address);
    }

    public function update(AddressRequest $request, $id)
    {
    	$data = $request->validated();
    	$address = $this->addressRepository->update($id, $data);
    	return response()->json($address);
    }

    public function delete($id = 0)
    {
        $ids = $this->addressRepository->delete($id);
        return response()->json($ids);
    }
}
