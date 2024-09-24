<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\ReviewController as ApiReviewController;
use App\Http\Requests\GetReviewsRequest;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\User\V3_1\ReviewCollection;
use App\Http\Resources\User\V3_1\ReviewResource;

class ReviewController extends ApiReviewController
{
    public function index(GetReviewsRequest $request)
    {
        return ReviewCollection::make(parent::index($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function create(ReviewRequest $request)
    {
        return ReviewResource::make(parent::create($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
