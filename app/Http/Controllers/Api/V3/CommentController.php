<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Api\CommentController as ApiCommentController;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;

class CommentController extends ApiCommentController
{
    public function index(Request $request)
    {
        return CommentCollection::make(parent::index($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function create(CommentRequest $request)
    {
        return CommentResource::make(parent::create($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function update(CommentRequest $request, $id)
    {
        return CommentResource::make(parent::update($request, $id))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function delete($id)
    {
        parent::delete($id);

        return $this->successJsonRes([], __('api.success'));
    }
}
