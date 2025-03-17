<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\ConsultationRepositoryInterface;
use App\Models\Consultation;

class ConsultationRepository extends EloquentRepository implements ConsultationRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     *
     * @param \App\Models\Consultation $model
     */
    public function __construct(Consultation $model)
    {
        parent::__construct($model);
    }
}
