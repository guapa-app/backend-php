<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Models\Vendor;

/**
 * Vendor repository.
 */
class VendorRepository extends EloquentRepository implements VendorRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     *
     * @param \App\Models\Vendor $model
     */
    public function __construct(Vendor $model)
    {
        parent::__construct($model);
    }
}
