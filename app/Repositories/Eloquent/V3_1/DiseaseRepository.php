<?php 

namespace App\Repositories\Eloquent\V3_1;

use App\Contracts\Repositories\V3_1\DiseaseRepositoryInterface;
use App\Models\Disease;
use App\Repositories\Eloquent\EloquentRepository;

class DiseaseRepository extends EloquentRepository implements DiseaseRepositoryInterface
{
    /**
     * Construct an instance of the repo.
     *
     * @param \App\Models\Disease $model
     */
    public function __construct(Disease $model)
    {
        parent::__construct($model);
    }
}