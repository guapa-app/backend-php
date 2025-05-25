<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Notifications\ChatMessage;
use App\Notifications\OfferStatusChanged;
use Carbon\Carbon;
use Hamedov\Messenger\Models\Conversation;
use Hamedov\Messenger\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use App\Services\NotificationMigrationHelper;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class MessagingService
{
    protected $productRepository;
    private $notificationHelper;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        NotificationMigrationHelper $notificationHelper
    ) {
        $this->productRepository = $productRepository;
        $this->notificationHelper = $notificationHelper;
    }

    /**
     * Send a new message.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $sender
     * @param  array  $data
     *
     * @return \Hamedov\Messenger\Models\Message
     */
    public function newMessage(Model $sender, array $data): Message
    {
        $type = $this->getMessageType($data['message']);

        // If this is an offer convert provided offer data to array
        // ['price' => 9789, 'status' => 'pending']
        if ($type === 'offer') {
            $data['message']['status'] = 'pending';
            $data['message'] = json_encode($data['message']);
        }

        if (isset($data['conversation_id'])) {
            // If conversation_id is provided
            // Send the message directly to the conversation
            $conversation = $sender->conversations()->where([
                'conversations.id' => $data['conversation_id'],
            ])->first();
            $message = $sender->sendMessageTo($conversation, $data['message'], null, $type);
        } else {
            // Otherwise the product_id must be present
            // Send the message to the product provider
            // and associate the product with the conversation
            $product = $this->productRepository->getOne($data['product_id']);

            // Send the message with params (Recipient, Message, Related Model)
            $message = $sender->sendMessageTo($product->vendor, $data['message'], $product, $type);
        }

        $message->loadMissing('participant', 'media');
        $sender->load('photo');
        $message->participant->setRelation('messageable', $sender);

        // We only have one recipient in this app logic
        $recipient = $message->recepients()->first()->messageable;

        // Send chat message notification via unified service
        $this->notificationHelper->sendChatMessageNotification($message, $recipient);

        return $message;
    }

    public function getMessageType($message)
    {
        // We will specify the type for our custom type only
        // Otherwise the package will handle the type
        if (!is_array($message) || !isset($message['price'])) {
            return null;
        }

        // This is an offer
        // ['price' => 9789, 'status' => 'pending']
        return 'offer';
    }

    /**
     * Get conversations for messageable model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $messageable
     * @param  array  $filters
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getConversations(Model $messageable, array $filters): LengthAwarePaginator
    {
        $conversations = $messageable->conversations()
            // ->withCount('participants')
            ->with([
                'relatable',
                'lastMessage',
                'lastMessage.participant.messageable',
                'participants',
                'participants.messageable',
                'participants.messageable.photo',
            ])->when(isset($filters['product_id']), function ($query) use ($filters) {
                $query->where('conversations.relatable_id', $filters['product_id']);
                $query->where('conversations.relatable_type', 'product');
            })->latest()->paginate($filters['perPage'] ?? 10);

        $conversations->getCollection()->transform(
            function ($conversation) use ($messageable) {
                // Get participant entity for the user fetching conversations
                $participant = $conversation->participants->first(function ($parti) use ($messageable) {
                    return $parti->messageable_id === $messageable->id &&
                        $parti->messageable_type === $messageable->getMorphClass();
                });

                // Get other participant
                $otherParticipant = $conversation->participants->first(function ($parti) use ($messageable) {
                    return $parti->messageable_id !== $messageable->id ||
                        $parti->messageable_type !== $messageable->getMorphClass();
                });

                // Specify whether this conversation has new messages
                $lastMessageReaders = (array) json_decode($conversation->lastMessage->read_by);
                $hasNewMessages = $conversation->lastMessage->participant->id != $participant->id &&
                    (!isset($lastMessageReaders[$participant->id]) ||
                        $conversation->lastMessage->created_at->gt($participant->last_read));

                return [
                    'id' => $conversation->id,
                    'name' => $conversation->name,
                    'product' => $conversation->relatable,
                    'other_party' => [
                        'id' => $otherParticipant->messageable_id,
                        'type' => $otherParticipant->messageable_type,
                        'participant_id' => $otherParticipant->id,
                        'name' => $otherParticipant->messageable->name,
                        'photo' => $otherParticipant->messageable->photo,
                    ],
                    'last_message' => [
                        'id' => $conversation->lastMessage->id,
                        'participant_id' => $conversation->lastMessage->participant_id,
                        'message' => $conversation->lastMessage->message,
                        'type' => $conversation->lastMessage->type,
                        'created_at' => $conversation->lastMessage->created_at,
                    ],
                    'has_new_messages' => $hasNewMessages,
                ];
            }
        );

        return $conversations;
    }

    /**
     * Get conversation messages.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $messageable
     * @param  int    $conversation_id
     * @param  array  $filters
     *
     * @return array
     */
    public function getMessages(Model $messageable, array $filters): LengthAwarePaginator
    {
        $conversation = $messageable->conversations()->where([
            'conversations.id' => $filters['conversation_id'] ?? 0,
        ])->first();

        if (!$conversation) {
            abort(404, 'Conversation not found');
        }

        $perPage = $filters['perPage'] ?? 15;
        $messages = $conversation->messages()->with([
            'participant',
            'participant.messageable',
            'media',
            'participant.messageable.photo',
        ])->latest()->paginate($perPage);

        return $messages;
    }

    /**
     * Mark conversation as read.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $messageable
     * @param  int    $conversation_id [description]
     *
     * @return \Hamedov\Messenger\Models\Conversation
     */
    public function markConversationAsRead(Model $messageable, int $conversation_id): Conversation
    {
        // Get user conversation
        $conversation = $messageable->conversations()->where([
            'conversations.id' => $conversation_id,
        ])->firstOrFail();

        // Get user participant entry for this conversation
        $participant = $conversation->participants()->where([
            'messageable_id' => $messageable->id,
            'messageable_type' => $messageable->getMorphClass(),
        ])->first();

        $timestamp = Carbon::now();

        $participant->update(['last_read' => $timestamp]);

        $conversation->messages()->where('read_by', 'not like', \DB::raw("'%\"" . $participant->id . "\":%'"))
            ->where('participant_id', '!=', $participant->id)
            ->update([
                'read_by' => \DB::raw('REPLACE(read_by, \'}\', \'"' . $participant->id . '":"' . $timestamp . '"}\')'),
            ]);

        return $conversation;
    }

    public function updateOfferStatus($message_id, $status)
    {
        $message = Message::with('participant')->where([
            'id' => $message_id,
            'type' => 'offer',
        ])->firstOrFail();

        $offer = $this->validateOfferStatus($message, $status);

        $message->message = $offer;

        $message->save();

        $notifiable = $status !== 'canceled' ?
            $message->participant->messageable :
            $message->conversation->relatable->user;

        // Send offer status change notification via unified service
        $this->notificationHelper->sendOfferStatusNotification($message, $status, $notifiable);

        return $message;
    }

    /**
     * Get message by id.
     *
     * @param  int $message_id
     *
     * @return \Hamedov\Messenger\Models\Message $message
     */
    public function getMessage($message_id)
    {
        return Message::findOrFail($message_id);
    }

    /**
     * Update offer status from default status (pending)
     * to (canceled, accepted or rejected).
     *
     * @param  \Hamedov\Messenger\Models\Message $message
     * @param  string $newStatus
     *
     * @return string
     */
    public function validateOfferStatus($message, $newStatus): string
    {
        $offer = (array) json_decode($message->message);

        $current = $offer['status'];

        if ($current !== 'pending') {
            $error = 'The offer is already ' . $current;
        }

        $currentUser = auth()->user();
        $participant = $message->participant;

        if (
            $newStatus === 'canceled' &&
            ($participant->messageable_id !== $currentUser->id
                || $participant->messageable_type !== $currentUser->getMorphClass())
        ) {
            $error = 'You can\'t cancel this offer';
        }

        if (
            $newStatus !== 'canceled' &&
            !$isOfferRecepient = $message->recepients()->messageable($currentUser)->count() === 1
        ) {
            $error = 'You can\'t ' . str_replace('ed', '', $newStatus) . ' this offer';
        }

        if (isset($error)) {
            throw ValidationException::withMessages([
                'status' => $error,
            ]);
        }

        $offer['status'] = $newStatus;

        return json_encode($offer);
    }
}
