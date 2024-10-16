<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\SupportMessageRepositoryInterface;
use App\Enums\SupportMessageSenderType;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3\SupportMessageRequest;
use App\Http\Resources\User\V3_1\SupportMessageCollection;
use App\Http\Resources\User\V3_1\SupportMessageResource;
use App\Http\Resources\User\V3_1\SupportMessageTypeCollection;
use App\Models\SupportMessageType;
use Illuminate\Http\Request;

class SupportMessageController extends BaseApiController
{
    private $supportMessageRepository;

    public function __construct(SupportMessageRepositoryInterface $supportMessageRepository)
    {
        parent::__construct();

        $this->supportMessageRepository = $supportMessageRepository;
    }
    /**
     * Contact support.
     *
     * @responseFile 200 responses/general/contact.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     *
     * @unauthenticated
     *
     * @param $request
     * @return SupportMessageCollection
     */
    public function index(Request $request)
    {
        $request->merge([
            'user_id' => $this->user->id,
        ]);

        $records = $this->supportMessageRepository->all($request);

        return SupportMessageCollection::make($records)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    /**
     * Contact support.
     *
     * @responseFile 200 responses/general/contact.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     *
     * @unauthenticated
     *
     * @param SupportMessageRequest $request
     * @return SupportMessageResource
     */
    public function create(SupportMessageRequest $request)
    {
        $data = $request->validated();
        $isAdmin = $this->isAdmin();
        $data['user_id'] = $this->user?->id;
        $data['phone'] ??= $this->user?->phone;
        $data['sender_type'] = $isAdmin ? SupportMessageSenderType::Admin : SupportMessageSenderType::User;

        $record = $this->supportMessageRepository->create($data);

        $record->load('supportMessageType');

        return SupportMessageResource::make($record)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single($id)
    {
        $record = $this->supportMessageRepository->getOneWithRelations($id);

        return SupportMessageResource::make($record)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function types()
    {
        $records = SupportMessageType::all();

        return SupportMessageTypeCollection::make($records)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
