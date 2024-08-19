<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redis;

/**
 * Favorites service.
 */
class FavoritesService
{
    private $repository;

    private $types;

    public function getFavorites(User $user, array $filters = []): LengthAwarePaginator
    {
        $type = $filters['type'] ?? 'product';
        $class = $this->getFavorableClass($type);

        return $user->favorites($class)->when($type == 'vendor', function ($query) {
            $query->with('logo');
        })->when($type == 'product', function ($query) {
            $query->with('vendor', 'media');
        })->when($type == 'offer', function ($query) {
            $query->with('product', 'product.media');
        })->when($type == 'post', function ($query) {
            $query->with('media', 'admin', 'category');
        })->paginate(10);
    }

    public function addFavorite(User $user, string $type, int $id): Model
    {
        $model = $this->getModelInstance($type, $id);
        $user->addFavorite($model);

        // Add current user id to the set of model likes in redis
        $model->addLike($user);

        return $model;
    }

    public function removeFavorite(User $user, string $type, int $id): Model
    {
        $model = $this->getModelInstance($type, $id);
        $user->removeFavorite($model);
        $model->removeLike($user);

        return $model;
    }

    public function getModelInstance(string $type, int $id): Model
    {
        $morphMap = Relation::morphMap();
        if (!isset($morphMap[$type]) ||
            !$model = (new $morphMap[$type])->findOrFail($id)) {
            abort(404);
        }

        return $model;
    }

    public function getFavorableClass(string $type): ?string
    {
        $morphMap = Relation::morphMap();

        return $morphMap[$type] ?? null;
    }
}
