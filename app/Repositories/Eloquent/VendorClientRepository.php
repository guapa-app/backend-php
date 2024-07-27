<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\VendorClientRepositoryInterface;
use App\Models\VendorClient;

/**
 * Vendor repository.
 */
class VendorClientRepository extends EloquentRepository implements VendorClientRepositoryInterface
{
    /**
     * @var int
     */
    public $perPage = 10;

    public function __construct(VendorClient $model)
    {
        parent::__construct($model);
    }
}
