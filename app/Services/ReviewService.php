<?php

namespace App\Services;

use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Http\Requests\GetReviewsRequest;
use App\Models\Order;
use App\Models\Review;
use App\Notifications\ReviewNotification;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Notification;

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

    public function getReviews(Request $request)
    {
        return  $this->reviewRepository->all($request);
    }

    public function create(array $data): Review
    {
        $order = Order::findorFail($data['order_id']);
        // Check if the user already reviewed this entity
        $oldReview = $order->reviews()->first();
        if ($oldReview) {
            abort(403, 'You have already reviewed this order');
        }

        // check if the user can review this entity
        if ($order->user_id != $data['user_id'] ) {
            abort(403, 'You are not allowed to review this order');
        }

        $data['stars'] =  collect($data['ratings'])
            ->pluck('rating') // Get all ratings
            ->avg(); // Calculate average
        $data['stars'] = round($data['stars'], 1);
        $review =  $this->reviewRepository->create($data);

        foreach ($data['ratings'] as $rating) {
            $review->ratings()->create($rating);
        }
        // handel images upload
        $this->handleMedia($review, $data);
        // load the relations
        $review->load('order','order.items', 'user', 'imageBefore', 'imageAfter');

        // send notification to the vendor
//        Notification::send($order->vendor, new ReviewNotification($order));

        return $review;
    }


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
}
