<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\CityRepositoryInterface;
use App\Models\City;

/**
 * City repository.
 */
class CityRepository extends EloquentRepository implements CityRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     * @param \App\Models\City $model
     */
    public function __construct(City $model)
    {
        parent::__construct($model);
    }
}
