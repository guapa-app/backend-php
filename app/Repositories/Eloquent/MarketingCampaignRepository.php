<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\MarketingCampaignRepositoryInterface;
use App\Models\MarketingCampaign;

class MarketingCampaignRepository extends EloquentRepository implements MarketingCampaignRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     *
     * @param \App\Models\MarketingCampaign $model
     */
    public function __construct(MarketingCampaign $model)
    {
        parent::__construct($model);
    }
}
