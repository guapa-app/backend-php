<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\SupportMessageRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class SupportMessageController extends BaseApiController
{
    private $supportMessageRepository;

    public function __construct(SupportMessageRepositoryInterface $supportMessageRepository)
    {
        parent::__construct();

        $this->supportMessageRepository = $supportMessageRepository;
    }

    /**
     * Register supportMessage.
     *
     * @responseFile 200 responses/supportMessages/create.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     *
     * @param $request
     * @return Model
     */
    public function createCommon($request)
    {
        $data = $request->validated();
        $data['user_id'] = $this->user?->id;
        $data['phone'] ??= $this->user?->phone;

        return $this->supportMessageRepository->create($data);
    }
}
