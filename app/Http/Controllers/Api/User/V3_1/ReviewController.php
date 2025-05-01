<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\GetReviewsRequest;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\User\V3_1\ReviewResource;
use App\Http\Resources\User\V3_1\ReviewCollection;

class ReviewController extends BaseApiController
{
    private $reviewService;

    /**
     * Constructor
     * 
     * @param ReviewService $reviewService
     */
    public function __construct(
        ReviewService $reviewService
    ) {
        parent::__construct();
        $this->reviewService = $reviewService;
    }

    /**
     * Get reviews with pagination
     * 
     * @param GetReviewsRequest $request
     * @return ReviewCollection
     */
    public function index(GetReviewsRequest $request)
    {
        $reviews = $this->reviewService->getReviews($request);

        return ReviewCollection::make($reviews)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    /**
     * Create a new review
     * 
     * @param ReviewRequest $request
     * @return ReviewResource|JsonResponse
     */
    public function create(ReviewRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $this->user->id;

        $result = $this->reviewService->create($data);

        // Check if the result is a JsonResponse (error occurred)
        if ($result instanceof JsonResponse) {
            return $result;
        }

        return ReviewResource::make($result)
            ->additional([
                'success' => true,
                'message' => __('api.review_created_successfully'),
            ]);
    }

    /**
     * Update review media
     * 
     * @param ReviewRequest $request
     * @param int $id Review ID
     * @return ReviewResource|JsonResponse
     */
    public function updateMedia(ReviewRequest $request, int $id)
    {
        // Find the review
        $review = \App\Models\Review::find($id);
        
        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => __('api.review_not_found')
            ], 404);
        }
        
        // Check if the user owns this review
        if ($review->user_id != $this->user->id) {
            return response()->json([
                'success' => false,
                'message' => __('api.unauthorized')
            ], 403);
        }
        
        $data = $request->validated();
        $updatedReview = $this->reviewService->updateReviewMedia($review, $data);
        
        return ReviewResource::make($updatedReview)
            ->additional([
                'success' => true,
                'message' => __('api.review_media_updated_successfully'),
            ]);
    }
}