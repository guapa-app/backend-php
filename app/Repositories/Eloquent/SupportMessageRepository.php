<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\SupportMessageRepositoryInterface;
use App\Models\SupportMessage;

/**
 * SupportMessage repository.
 */
class SupportMessageRepository extends EloquentRepository implements SupportMessageRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     *
     * @param \App\Models\SupportMessage $model
     */
    public function __construct(SupportMessage $model)
    {
        parent::__construct($model);
    }
}
