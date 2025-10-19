<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Order;
use App\Models\Review;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\GetReviewsRequest;
use App\Notifications\ReviewNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\Repositories\ReviewRepositoryInterface;

/**
 * Review service.
 */
class ReviewService
{
    private $reviewRepository;

    public function __construct(ReviewRepositoryInterface $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * Get all reviews based on request parameters
     * 
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getReviews(Request $request)
    {
        return $this->reviewRepository->all($request);
    }

    /**
     * Create a review for an order or consultation
     *
     * @param array $data
     * @return Review|\Illuminate\Http\JsonResponse
     */
    public function create(array $data)
    {
        // Identify the reviewable entity
        $reviewableType = $data['reviewable_type'] ?? null;
        $reviewableId = $data['reviewable_id'] ?? null;

        if (!$reviewableType && isset($data['order_id'])) {
            $reviewableType = Order::class;
            $reviewableId = $data['order_id'];
        } else if (!$reviewableType && isset($data['consultation_id'])) {
            $reviewableType = Consultation::class;
            $reviewableId = $data['consultation_id'];
        }

        // Find the reviewable entity
        $reviewable = $this->findReviewableEntity($reviewableType, $reviewableId);
        
        // If findReviewableEntity returned a response, return it
        if ($reviewable instanceof \Illuminate\Http\JsonResponse) {
            return $reviewable;
        }

        // Check if the user already reviewed this entity
        $oldReview = $reviewable->reviews()->where('user_id', $data['user_id'])->first();
        if ($oldReview) {
            return $oldReview;
        }

        // Check if the user can review this entity
        $validationResponse = $this->validateUserCanReview($reviewable, $data['user_id']);
        if ($validationResponse) {
            return $validationResponse;
        }

        // Calculate average rating
        $data['stars'] = collect($data['ratings'])
            ->pluck('rating')
            ->avg();
        $data['stars'] = round($data['stars'], 1);

        // Create the review using polymorphic relationship
        $review = $reviewable->reviews()->create([
            'user_id' => $data['user_id'],
            'stars' => $data['stars'],
            'comment' => $data['comment'] ?? '',
            'reviewable_type' => $reviewableType,
        ]);

        // Create individual ratings
        foreach ($data['ratings'] as $rating) {
            $review->ratings()->create($rating);
        }

        // Handle media uploads
        $this->handleMedia($review, $data);

        // Load the relations
        $review->load('user', 'reviewable', 'imageBefore', 'imageAfter');

        // Send notification based on reviewable type
        $this->sendNotifications($review);

        return $review;
    }

    /**
     * Find the reviewable entity
     *
     * @param string|null $reviewableType
     * @param int|null $reviewableId
     * @return Model|\Illuminate\Http\JsonResponse
     */
    private function findReviewableEntity(?string $reviewableType, ?int $reviewableId)
    {
        // Check if reviewable type and ID are provided
        if (!$reviewableType || !$reviewableId) {
            return response()->json([
                'success' => false,
                'message' => 'Reviewable entity type or ID is missing'
            ], 400);
        }

        // Normalize the class name if needed
        if (!str_contains($reviewableType, '\\')) {
            $reviewableType = 'App\\Models\\' . ucfirst($reviewableType);
        }

        // Validate the entity type
        if (!in_array($reviewableType, [Order::class, Consultation::class])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reviewable entity type'
            ], 400);
        }

        try {
            $entity = $reviewableType::findOrFail($reviewableId);
            return $entity;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Reviewable entity not found'
            ], 404);
        }
    }

    /**
     * Get friendly name for entity type
     *
     * @param Model $entity
     * @return string
     */
    private function getEntityTypeName(Model $entity): string
    {
        if ($entity instanceof Order) {
            return 'order';
        } elseif ($entity instanceof Consultation) {
            return 'consultation';
        }

        return 'item';
    }

    /**
     * Validate if user can review the entity
     *
     * @param Model $reviewable
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse|null
     */
    private function validateUserCanReview(Model $reviewable, int $userId)
    {
        if ($reviewable instanceof Order) {
            if ($reviewable->user_id != $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to review this order'
                ], 403);
            }
            
            // Optional: Add order-specific validation here
            if ($reviewable->status !== 'completed' && $reviewable->status !== 'delivered') {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only review completed or delivered orders'
                ], 403);
            }
        } elseif ($reviewable instanceof Consultation) {
            if ($reviewable->user_id != $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to review this consultation'
                ], 403);
            }
            
            // Additional validation to ensure only the consultation user can review
            if ($reviewable->status !== 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only review completed consultations'
                ], 403);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reviewable entity type'
            ], 400);
        }
        
        return null;
    }

    /**
     * Send notifications based on the reviewable type
     *
     * @param Review $review
     * @return void
     */
    private function sendNotifications(Review $review): void
    {
        $reviewable = $review->reviewable;

        if ($reviewable instanceof Order) {
            // Notify vendor about new review
            if ($reviewable->vendor) {
                Notification::send($reviewable->vendor, new ReviewNotification($review));
            }
        } elseif ($reviewable instanceof Consultation) {
            // Add consultation-specific notification logic here
            if (isset($reviewable->consultant)) {
                // Uncomment when ConsultationReviewNotification is created
                // Notification::send($reviewable->consultant, new ConsultationReviewNotification($review));
            }
        }
    }

    /**
     * Handle media uploads for the review
     *
     * @param Review $review
     * @param array $data
     * @return void
     */
    private function handleMedia(Review $review, array $data): void
    {
        if (isset($data['image_before'])) {
            if ($data['image_before'] instanceof UploadedFile) {
                $review->addMedia($data['image_before'])->toMediaCollection('before');
            } elseif (is_string($data['image_before']) && str_contains($data['image_before'], ';base64')) {
                $review->addMediaFromBase64($data['image_before'])->toMediaCollection('before');
            }
        }

        if (isset($data['image_after'])) {
            if ($data['image_after'] instanceof UploadedFile) {
                $review->addMedia($data['image_after'])->toMediaCollection('after');
            } elseif (is_string($data['image_after']) && str_contains($data['image_after'], ';base64')) {
                $review->addMediaFromBase64($data['image_after'])->toMediaCollection('after');
            }
        }
    }

    /**
     * Update media for an existing review
     *
     * @param Review $review
     * @param array $data
     * @return Review
     */
    public function updateReviewMedia(Review $review, array $data): Review
    {
        $keep_media = $data['keep_media'] ?? [];
        $review->media()->whereNotIn('id', $keep_media)->delete();

        $mediaCollections = [
            'before_media_ids' => 'before',
            'after_media_ids' => 'after'
        ];

        foreach ($mediaCollections as $mediaKey => $collectionName) {
            if (!empty($data[$mediaKey])) {
                Media::whereIn('id', $data[$mediaKey])
                    ->update([
                        'model_type' => 'App\\Models\\Review',
                        'model_id' => $review->id,
                        'collection_name' => $collectionName
                    ]);
            }
        }

        $review->load(['imageBefore', 'imageAfter']);

        return $review;
    }
}