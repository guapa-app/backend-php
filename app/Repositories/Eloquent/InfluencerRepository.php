<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\InfluencerRepositoryInterface;
use App\Models\Influencer;

class InfluencerRepository extends EloquentRepository implements InfluencerRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     *
     * @param \App\Models\Influencer $model
     */
    public function __construct(Influencer $model)
    {
        parent::__construct($model);
    }
}
