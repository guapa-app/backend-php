<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Contracts\Repositories\CityRepositoryInterface;
use Illuminate\Http\Request;

class CityController extends Controller
{
	private $cityRepository;

    public function __construct(CityRepositoryInterface $cityRepository)
    {
    	$this->cityRepository = $cityRepository;
    }

    public function index(Request $request)
    {
    	$cities = $this->cityRepository->all($request);
    	return response()->json($cities);
    }

    public function single($id)
    {
    	$city = $this->cityRepository->getOneWithRelations($id);
    	return response()->json($city);
    }

    public function create(CityRequest $request)
    {
    	$data = $request->validated();
    	$city = $this->cityRepository->create($data);
    	return response()->json($city);
    }

    public function update(CityRequest $request, $id)
    {
    	$data = $request->validated();
    	$city = $this->cityRepository->update($id, $data);
    	return response()->json($city);
    }

    public function delete($id = 0)
    {
        $this->cityRepository->delete($id);
        return response()->json([
            'message' => $id,
        ]);
    }
}
