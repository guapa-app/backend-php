<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Models\Order;

/**
 * Order repository.
 */
class OrderRepository extends EloquentRepository implements OrderRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     * @param \App\Models\Order $model
     */
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }
}
