<?php

namespace App\Repositories\Eloquent\V3_1;

use App\Contracts\Repositories\V3_1\BkamConsultationRepositoryInterface;
use App\Models\BkamConsultation;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Http\Request;

class BkamConsultationRepository extends EloquentRepository implements BkamConsultationRepositoryInterface
{
    /**
     * Construct an instance of the repo.
     *
     * @param \App\Models\BkamConsultation $model
     */
    public function __construct(BkamConsultation $model)
    {
        parent::__construct($model);
    }
}
