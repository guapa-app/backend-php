<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Api\FavoriteController as ApiFavoriteController;
use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\Vendor\V3_1\FavoriteCollection;
use App\Http\Resources\Vendor\V3_1\FavoriteResource;
use App\Services\FavoritesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends BaseApiController
{
    protected $favoritesService;

    public function __construct(FavoritesService $favoritesService)
    {
        parent::__construct();

        $this->favoritesService = $favoritesService;
    }

    public function index(Request $request)
    {
        $favorites =$this->favoritesService->getFavorites($this->user, $request->all());

        return FavoriteCollection::make($favorites)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function create(FavoriteRequest $request)
    {
        $data = $request->validated();

        $record = $this->favoritesService
            ->addFavorite($this->user, $data['type'], $data['id']);

        return FavoriteResource::make($record)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function delete($type, $id): JsonResponse
    {
        $this->favoritesService->removeFavorite($this->user, $type, $id);

        return $this->successJsonRes([
            'is_deleted' => true,
        ], __('api.favourite_deleted'));
    }
}
