<?php
namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetReviewsRequest;
use App\Http\Requests\ReviewRequest;
use App\Services\ReviewService;
use Illuminate\Http\Request;

/**
 * @group Reviews
 */
class ReviewController extends BaseApiController
{
    private $reviewService;
    private $reviewRepository;

    public function __construct(ReviewRepositoryInterface $reviewRepository,
        ReviewService $reviewService)
    {
        parent::__construct();
        
        $this->reviewRepository = $reviewRepository;
        $this->reviewService = $reviewService;
    }

    /**
     * List reviews
     *
     * @responseFile 200 scenario="Paginated reviews list" responses/reviews/list.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function index(GetReviewsRequest $request)
	{
        $reviews = $this->reviewService->getReviews($request->validated());

        $reviews->getCollection()->transform(function($review) {
            $review->comment = strip_tags($review->comment);
            return $review;
        });

        return response()->json($reviews);
	}

    /**
     * Create review
     *
     * @responseFile 200 responses/reviews/create.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 404 scenario="Reviewable entity not found" responses/errors/404.json
     * @responseFile 403 scenario="Already reviewed" responses/reviews/already-reviewed.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     * 
     * @param  \App\Http\Requests\ReviewRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function create(ReviewRequest $request)
	{
        $data = $request->validated();
        $data['user_id'] = $this->user->id;
        $review = $this->reviewService->create($data);
        return response()->json($review);
	}
}
