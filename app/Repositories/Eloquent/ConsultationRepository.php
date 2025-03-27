<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\ConsultationRepositoryInterface;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationRepository extends EloquentRepository implements ConsultationRepositoryInterface
{
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
