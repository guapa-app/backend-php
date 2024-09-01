<?php

namespace App\Repositories\Eloquent\V3_1;

use App\Contracts\Repositories\V3_1\OfferRepositoryInterface;
use App\Enums\ListTypeEnum;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

/**
 * Offer repository.
 */
class OfferRepository implements OfferRepositoryInterface
{
    /**
     * Construct an instance of the repo.
     *
     * @param  Product  $model
     */
    public function __construct(public Product $model)
    {
    }

    public function getData(int $limit = null): Collection
    {
        return $this->model
            ->with('offer')
            ->where('type', ListTypeEnum::Offers->value)
            ->limit($limit)
            ->get();
    }
}
