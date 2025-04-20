<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\GetReviewsRequest;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\User\V3_1\ReviewCollection;
use App\Http\Resources\User\V3_1\ReviewResource;
use App\Services\ReviewService;

class ReviewController extends BaseApiController
{
    private $reviewService;

    public function __construct(
        ReviewService $reviewService
    ) {
        parent::__construct();
        $this->reviewService = $reviewService;
    }
    public function index(GetReviewsRequest $request)
    {
        $reviews = $this->reviewService->getReviews( $request);

        return  ReviewCollection::make($reviews)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function create(ReviewRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $this->user->id;

        return ReviewResource::make($this->reviewService->create($data))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
