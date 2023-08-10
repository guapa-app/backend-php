<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ReviewController as ApiReviewController;
use App\Http\Requests\GetReviewsRequest;
use App\Http\Requests\ReviewRequest;

class ReviewController extends ApiReviewController
{
    public function index(GetReviewsRequest $request)
    {
        return response()->json(parent::index($request));
    }

    public function create(ReviewRequest $request)
    {
        return response()->json(parent::create($request));
    }
}
