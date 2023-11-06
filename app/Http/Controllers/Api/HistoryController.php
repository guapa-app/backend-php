<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\HistoryRepositoryInterface;
use App\Http\Requests\HistoryRequest;
use Illuminate\Http\Request;

/**
 * @group History
 */
class HistoryController extends BaseApiController
{
    private $historyRepository;

    public function __construct(HistoryRepositoryInterface $historyRepository)
    {
        parent::__construct();

        $this->historyRepository = $historyRepository;
    }

    /**
     * History list.
     *
     * @queryParam date string History date. Example: 2021-01-01
     * @queryParam page integer Page number. Example: 1
     * @queryParam perPage integer Records to fetch per page. Example: 10
     *
     * @responseFile 200 responses/history/list.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->merge(['user_id' => $this->user->id]);
        $history = $this->historyRepository->all($request);

        return response()->json($history);
    }

    /**
     * History details.
     *
     * @urlParam id integer required History id. Example: 2
     *
     * @responseFile 200 responses/history/details.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 404 scenario="Not found" responses/errors/404.json
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function single($id = 0)
    {
        $history = $this->historyRepository->getOneOrFail($id, [
            'user_id' => $this->user->id,
        ]);

        return response()->json($history);
    }

    /**
     * Create history.
     *
     * @responseFile 200 responses/history/create.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     *
     * @bodyParam details string required History details. Example: Caught a flu
     * @bodyParam record_date string required History date. Example: 2021-01-01
     *
     * @param  \App\Http\Requests\HistoryRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(HistoryRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $this->user->id;
        $history = $this->historyRepository->create($data);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $history->addMedia($data['image'])->toMediaCollection('history_images');
        }

        $history->load('image');

        return response()->json($history);
    }

    /**
     * Update history.
     *
     * @responseFile 200 responses/history/create.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 404 scenario="Not found" responses/errors/404.json
     *
     * @urlParam id integer required History id. Example: 3
     * @bodyParam details string required History details. Example: Caught a flu
     * @bodyParam record_date string required History date. Example: 2021-01-01
     *
     * @param  \App\Http\Requests\HistoryRequest $request
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(HistoryRequest $request, $id = 0)
    {
        $history = $this->historyRepository->update($id, $request->validated(), [
            'user_id' => $this->user->id,
        ]);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $history->addMedia($data['image'])->toMediaCollection('history_images');
        } elseif ($this->historyRepository->isAdmin() && !isset($data['image'])) {
            $history->media()->delete();
        }

        $history->load('image');

        return response()->json($history);
    }

    /**
     * Delete history.
     *
     * @responseFile 200 responses/history/delete.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 404 scenario="Not found" responses/errors/404.json
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id = 0)
    {
        $ids = $this->historyRepository->delete($id, [
            'user_id' => $this->user->id,
        ]);

        return response()->json($ids);
    }
}
