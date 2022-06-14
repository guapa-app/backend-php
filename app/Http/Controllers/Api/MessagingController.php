<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Http\Requests\MessagingRequest;
use App\Services\MessagingService;

/**
 * @group Chat
 */
class MessagingController extends BaseApiController
{
    protected $messagingService;
    private $vendorRepository;

    public function __construct(MessagingService $messagingService,
        VendorRepositoryInterface $vendorRepository)
    {
        parent::__construct();
        
        $this->messagingService = $messagingService;
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * Send message
     *
     * @responseFile 200 responses/chat/new-message.json
     * @responseFile 200 scenario="Send message as vendor" responses/chat/new-message-by-vendor.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 404 scenario="For vendor app when provided vendor_id is invalid" responses/errors/404.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * 
     * @param  \App\Http\Requests\MessagingRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(MessagingRequest $request)
    {
    	$data = $request->validated();

        $sender = $this->user;
        
        if (isset($data['vendor_id'])) {
            $sender = $this->vendorRepository->getOneOrFail($data['vendor_id']);
        }

    	$message = $this->messagingService->newMessage($sender, $data);
    	return response()->json($message);
    }

    /**
     * Get conversations
     *
     * @responseFile 200 responses/chat/conversations.json
     * @responseFile 200 scenario="Get vendor conversations" responses/chat/conversations-vendor.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 404 scenario="For vendor app when provided vendor_id is invalid" responses/errors/404.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     *
     * @queryParam product_id Fetch Filter conversations by product.
     * @queryParam vendor_id Required to fetch conversations for vendor app.
     * @queryParam page Page number for pagination Example: 2
     * @queryParam perPage Results to fetch per page Example: 15
     * 
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function conversations(Request $request)
    {
        $messageable = $this->user;
        if ($request->has('vendor_id')) {
            $messageable = $this->vendorRepository->getOneOrFail($request->get('vendor_id'));
        }

        $conversations = $this->messagingService->getConversations($messageable, $request->all());
    	return response()->json($conversations);
    }

    /**
     * Get conversation messages
     *
     * @responseFile 200 responses/chat/messages.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 404 scenario="Conversation not found or for vendor app when provided vendor_id is invalid" responses/errors/404.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * 
     * @queryParam conversation_id required Conversation id to fetch messages for.
     * @queryParam vendor_id Required to fetch messages for vendor app.
     * @queryParam page            Page number for pagination Example: 2
     * @queryParam perPage         Results per page Example: 15
     * 
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function messages(Request $request)
    {
        $filters = $request->all();

        $messageable = $this->user;
        if ($request->has('vendor_id')) {
            $messageable = $this->vendorRepository->getOneOrFail($request->get('vendor_id'));
        }

    	$messages = $this->messagingService->getMessages($messageable, $filters);

    	return response()->json($messages);
    }

    /**
     * Mark conversation as read
     *
     * @responseFile 200 responses/chat/mark-conversation-as-read.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 404 scenario="Conversation not found or for vendor app when provided vendor_id is invalid" responses/errors/404.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     *
     * @urlParam conversation_id required Conversation to mark as read.
     * @queryParam vendor_id Required for vendor app.
     * 
     * @param  \Illuminate\Http\Request $request
     * @param  int  $conversationId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function markConversationAsRead(Request $request, $conversationId)
    {
        $messageable = $this->user;

        if ($request->has('vendor_id')) {
            $messageable = $this->vendorRepository->getOneOrFail($request->get('vendor_id'));
        }

    	$conversation = $this->messagingService->markConversationAsRead($messageable, (int) $conversationId);
    	return response()->json($conversation);
    }

    /**
     * Update offer status
     *
     * @authenticated
     *
     * @urlParam  id     required        Message id
     * @bodyParam status string required Offer status `accepted`, `rejected` or `canceled`
     * 
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $messageId
     * 
     * @return \Illuminate\Http\Response
     */
    public function updateOfferStatus(Request $request, $messageId)
    {
        $data = $this->validate($request, [
            'status' => 'required|string|in:accepted,rejected,canceled',
        ]);

        $status = $data['status'];

        $message = $this->messagingService->updateOfferStatus($messageId, $status);

        return response()->json($message);
    }
}
