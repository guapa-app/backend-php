<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Api\FavoriteController as ApiFavoriteController;
use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\FavoriteCollection;
use App\Http\Resources\FavoriteResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends ApiFavoriteController
{
    public function index(Request $request)
    {
        return FavoriteCollection::make(parent::index($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function create(FavoriteRequest $request)
    {
        return FavoriteResource::make(parent::create($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function delete($type, $id): JsonResponse
    {
        parent::delete($type, $id);

        return $this->successJsonRes([
            'is_deleted' => true,
        ], __('api.favourite_deleted'));
    }
}
