<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Api\PostController as ApiPostController;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;

class PostController extends ApiPostController
{
    public function index(Request $request)
    {
        $index = parent::index($request);

        return PostCollection::make($index)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single($id)
    {
        return PostResource::make(parent::single($id))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
