<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;

/**
 * Product repository.
 */
class ProductRepository extends EloquentRepository implements ProductRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     *
     * @param \App\Models\Product $model
     */
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }
}
