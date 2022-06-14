<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Requests\HistoryRequest;
use App\Contracts\Repositories\HistoryRepositoryInterface;

class HistoryController extends BaseAdminController
{
    private $historyRepository;

    public function __construct(HistoryRepositoryInterface $historyRepository)
    {
        parent::__construct();
        
        $this->historyRepository = $historyRepository;
    }
    
	public function index(Request $request)
	{
        $history = $this->historyRepository->all($request);
        return response()->json($history);
	}

	public function single($id = 0)
	{
		$history = $this->historyRepository->getOneOrFail($id);
        return response()->json($history);
	}

	public function create(HistoryRequest $request)
	{
        $data = $request->validated();
        $history = $this->historyRepository->create($data);
        return response()->json($history);
	}

    public function update(HistoryRequest $request, $id = 0)
    {
        $history = $this->historyRepository->update($id, $request->validated());
        return response()->json($history);
    }

    public function delete($id = 0)
    {
        $ids = $this->historyRepository->delete($id);
        return response()->json($ids);
    }
}
