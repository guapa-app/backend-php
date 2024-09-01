<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\PostController as ApiPostController;
use App\Http\Resources\V3\PostCollection;
use App\Http\Resources\V3\PostResource;
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
