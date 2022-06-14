<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\FavoriteRequest;
use App\Services\FavoritesService;

/**
 * @group Favorites
 *
 */
class FavoriteController extends BaseApiController
{
	protected $favoritesService;

	public function __construct(FavoritesService $favoritesService)
	{
        parent::__construct();

		$this->favoritesService = $favoritesService;
	}

    /**
     * Get favorites
     *
     * @authenticated
     *
     * @responseFile 200 scenario="List favorite vendors" responses/favorites/list-vendors.json
     * @responseFile 200 scenario="List favorite products" responses/favorites/list-products.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     *
     * @queryParam type Type of favorites to return (product, vendor, post). Example: product
     * @queryParam page Page number for pagination. Example: 2
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
    	$favorites = $this->favoritesService->getFavorites(
            $this->user, $request->all()
        );

    	return response()->json($favorites);
    }

    /**
     * Add entity to favorites
     *
     * @responseFile 200 scenario="Add product to favorites" responses/favorites/add-product.json
     * @responseFile 200 scenario="Add vendor to favorites" responses/favorites/add-vendor.json
     * @responseFile 404 scenario="Invalid id/type" responses/errors/404.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     *
     * @param  \App\Http\Requests\FavoriteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(FavoriteRequest $request)
    {
    	$data = $request->validated();
    	$favorite = $this->favoritesService->addFavorite(
            $this->user, $data['type'], $data['id']
        );

    	return response()->json($favorite);
    }

    /**
     * Delete Favorite
     *
     * @responseFile 200 scenario="Remove entity from favorites" responses/favorites/remove-favorite.json
     * @responseFile 404 scenario="Invalid id/type" responses/errors/404.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     *
     * @urlParam type required int required Example: product
     * @urlParam id required int required Example: 1
     *
     * @param string $type
     * @param int $id
     * @return \Illuminat\Http\JsonResponse
     *
     */
    public function delete($type, $id): JsonResponse
    {
        $this->favoritesService->removeFavorite(
            $this->user, $type, $id
        );

        return response()->json([
            'message' => __('api.favourite_deleted'),
            'id' => $id,
            'type' => $type,
        ]);
    }
}
