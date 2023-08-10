<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\CommentController as ApiCommentController;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;

class CommentController extends ApiCommentController
{
    public function index(Request $request)
    {
        return response()->json(parent::index($request));
    }

    public function create(CommentRequest $request)
    {
        return response()->json(parent::create($request));
    }

    public function update(CommentRequest $request, $id)
    {
        return response()->json(parent::update($request, $id));
    }

    public function delete($id)
    {
        return response()->json(parent::delete($id));
    }
}
