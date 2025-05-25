<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\FavoriteRequest;
use App\Notifications\NewLikeNotification;
use App\Services\FavoritesService;
use App\Services\NotificationInterceptor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @group Favorites
 */
class FavoriteController extends BaseApiController
{
    protected $favoritesService;
    protected $notificationInterceptor;

    public function __construct(
        FavoritesService $favoritesService,
        NotificationInterceptor $notificationInterceptor
    ) {
        parent::__construct();

        $this->favoritesService = $favoritesService;
        $this->notificationInterceptor = $notificationInterceptor;
    }

    /**
     * Get favorites.
     *
     * @authenticated
     *
     * @responseFile 200 scenario="List favorite vendors" responses/favorites/list-vendors.json
     * @responseFile 200 scenario="List favorite products" responses/favorites/list-products.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     *
     * @queryParam type of favorites to return (product, vendor, post). Example: product
     * @queryParam page number for pagination. Example: 2
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {
        return $this->favoritesService
            ->getFavorites($this->user, $request->all());
    }

    /**
     * Add entity to favorites.
     *
     * @responseFile 200 scenario="Add product to favorites" responses/favorites/add-product.json
     * @responseFile 200 scenario="Add vendor to favorites" responses/favorites/add-vendor.json
     * @responseFile 404 scenario="Invalid id/type" responses/errors/404.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     *
     * @param FavoriteRequest $request
     * @return Model
     */
    public function create(FavoriteRequest $request)
    {
        $data = $request->validated();

        $model = $this->favoritesService
            ->addFavorite($this->user, $data['type'], $data['id']);

        // send notification if type is post using unified service
        if ($data['type'] === 'post') {
            $this->notificationInterceptor->interceptSingle(
                $model->user,
                new NewLikeNotification($this->user, $model)
            );
        }
        return $model;
    }

    /**
     * Delete Favorite.
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
     */
    public function delete($type, $id)
    {
        return $this->favoritesService->removeFavorite($this->user, $type, $id);
    }
}
