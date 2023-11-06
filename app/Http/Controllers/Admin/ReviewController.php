<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Http\Requests\ReviewRequest;
use App\Services\ReviewService;
use Illuminate\Http\Request;

/**
 * @group Reviews
 */
class ReviewController extends BaseAdminController
{
    private $reviewService;
    private $reviewRepository;

    public function __construct(
        ReviewRepositoryInterface $reviewRepository,
        ReviewService $reviewService
    ) {
        parent::__construct();

        $this->reviewRepository = $reviewRepository;
        $this->reviewService = $reviewService;
    }

    /**
     * List reviews.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $reviews = $this->reviewRepository->all($request);

        return response()->json($reviews);
    }

    public function single($id)
    {
        $review = $this->reviewRepository->getOneWithRelations((int) $id);

        return response()->json($review);
    }

    public function create(ReviewRequest $request)
    {
        $data = $request->validated();
        $review = $this->reviewRepository->create($data);

        return response()->json($review);
    }

    public function update(ReviewRequest $request, $id = 0)
    {
        $review = $this->reviewRepository->update($id, $request->validated());
        $review->load('reviewable');

        return response()->json($review);
    }

    public function delete($id = 0)
    {
        $this->reviewRepository->delete($id);

        return response()->json([
            'message' => $id,
        ]);
    }
}
