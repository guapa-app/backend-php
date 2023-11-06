<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\AddressRepositoryInterface;
use App\Models\Address;

/**
 * Address repository.
 */
class AddressRepository extends EloquentRepository implements AddressRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     * @param \App\Models\Address $model
     */
    public function __construct(Address $model)
    {
        parent::__construct($model);
    }
}
