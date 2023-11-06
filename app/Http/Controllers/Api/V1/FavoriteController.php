<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\FavoriteController as ApiFavoriteController;
use App\Http\Requests\FavoriteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends ApiFavoriteController
{
    public function index(Request $request)
    {
        return response()->json(parent::index($request));
    }

    public function create(FavoriteRequest $request)
    {
        return response()->json(parent::create($request));
    }

    public function delete($type, $id): JsonResponse
    {
        parent::delete($type, $id);

        return response()->json([
            'id'      => $id,
            'type'    => $type,
            'message' => __('api.favourite_deleted'),
        ]);
    }
}
