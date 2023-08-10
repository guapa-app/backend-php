<?php

namespace App\Services;

use App\Contracts\HasReviews;
use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Models\Review;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Review service
 */
class ReviewService
{
    private $reviewRepository;

    public function __construct(ReviewRepositoryInterface $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function getReviews(array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 15;
        return Review::where([
            'reviewable_type' => $filters['reviewable_type'],
            'reviewable_id' => $filters['reviewable_id'],
        ])->latest()->with('user')->paginate($perPage);
    }

    public function create(array $data): Review
    {
        $model = $this->getModelInstance($data['reviewable_type'], $data['reviewable_id']);
        // Check if the user already reviewed this entity
        $oldReview = $model->reviews()->where('user_id', $data['user_id'])->first();
        if ($oldReview) {
            abort(403, 'You have already reviewed this ' . $data['reviewable_type']);
        }

        return $this->reviewRepository->create($data);
    }

    public function getModelInstance(string $type, int $id): HasReviews
    {
        $morphMap = Relation::morphMap();
        if (!isset($morphMap[$type]) ||
            !$model = (new $morphMap[$type])->findOrFail($id)) {
            abort(404);
        }

        return $model;
    }

    public function getReviewableClass(string $type): ?string
    {
        $morphMap = Relation::morphMap();
        return $morphMap[$type] ?? null;
    }
}
