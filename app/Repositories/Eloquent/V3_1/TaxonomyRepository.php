<?php

namespace App\Repositories\Eloquent\V3_1;

use App\Contracts\Repositories\V3_1\TaxonomyRepositoryInterface;
use App\Models\Taxonomy;
use Illuminate\Database\Eloquent\Collection;

/**
 * Taxonomy Repository.
 */
class TaxonomyRepository implements TaxonomyRepositoryInterface
{
    /**
     * Construct an instance of the repo.
     *
     * @param  \App\Models\Taxonomy  $model
     */
    public function __construct(public Taxonomy $model)
    {
    }

    /**
     * Get categories list for api common data.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getData(array $where = [], array $with = [], int $limit = null): Collection
    {
        return $this->model->isRoot()->with($with)->where($where)->limit($limit)->get();
    }
}
