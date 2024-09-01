<?php

namespace App\Repositories\Eloquent\V3_1;

use App\Contracts\Repositories\V3_1\ProductRepositoryInterface;
use App\Enums\ListTypeEnum;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

/**
 * Product repository.
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Construct an instance of the repo.
     *
     * @param  Product  $model
     */
    public function __construct(public Product $model)
    {
    }

    public function getData(string $with = "", int $limit = null): Collection
    {
        return $this->model
            ->with('offer', $with)
            ->where('type', ListTypeEnum::Product->value)
            ->limit($limit)
            ->get();
    }
}
