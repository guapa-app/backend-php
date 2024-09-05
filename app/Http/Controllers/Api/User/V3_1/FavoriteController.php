<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\FavoriteController as ApiFavoriteController;
use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\V3_1\User\FavoriteCollection;
use App\Http\Resources\V3_1\User\FavoriteResource;
use App\Models\User;
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
        if (!in_array($type, User::FAVORITE_TYPES) || !is_numeric($id)) {
            return $this->errorJsonRes(message: __('api.query_error'));
        }
        try {
            parent::delete($type, $id);

            return $this->successJsonRes(['is_deleted' => true], __('api.favourite_deleted'));
        } catch (\Exception $e) {
          return $this->errorJsonRes(message: __('api.error_occurred'));
        }
    }
}
