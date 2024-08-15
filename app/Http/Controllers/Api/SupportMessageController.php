<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\SupportMessageRepositoryInterface;
use App\Enums\SupportMessageSenderType;
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
     * List supportMessages.
     *
     * @param $request
     * @return Model
     */
    public function indexCommon($request)
    {
        return $this->supportMessageRepository->all($request);
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
        $isAdmin = $this->isAdmin();
        $data['user_id'] = $this->user?->id;
        $data['phone'] ??= $this->user?->phone;
        $data['sender_type'] = $isAdmin ? SupportMessageSenderType::Admin : SupportMessageSenderType::User;

        return $this->supportMessageRepository->create($data);
    }

    /**
     * Get supportMessage details.
     *
     * @unauthenticated
     *
     * @responseFile 200 responses/supportMessages/details.json
     * @responseFile 404 scenario="Support Message not found" responses/errors/404.json
     *
     * @urlParam id required Message id
     *
     * @param int $id
     * @return Model
     */
    public function singleCommon($id)
    {
        return $this->supportMessageRepository->getOneWithRelations($id);
    }
}
