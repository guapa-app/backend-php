<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\SocialMediaRepositoryInterface;
use App\Models\SocialMedia;
use App\Models\Vendor;

class SocialMediaRepository extends EloquentRepository implements SocialMediaRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     *
     * @param Vendor $model
     */
    public function __construct(SocialMedia $model)
    {
        parent::__construct($model);
    }
}
