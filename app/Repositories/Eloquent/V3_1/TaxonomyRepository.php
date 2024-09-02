<?php

namespace App\Repositories\Eloquent\V3_1;

use App\Contracts\Repositories\V3_1\TaxonomyRepositoryInterface;
use App\Models\Taxonomy;
use App\Repositories\Eloquent\TaxRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Taxonomy Repository.
 */
class TaxonomyRepository extends TaxRepository implements TaxonomyRepositoryInterface
{
    /**
     * Items per page for pagination.
     *
     * @var int
     */
    public $perPage = 4;

    /**
     * Get categories list for api common data.
     *
     * @return Collection|LengthAwarePaginator
     */
    public function getData(
        array $with = [],
        array $where = [],
        bool $isPaginated = false
    ): Collection|LengthAwarePaginator {
        $taxonomy = Taxonomy::isRoot()->with($with)->where($where);

        $isPaginated ? $taxonomy = $taxonomy->paginate($this->perPage) : $taxonomy = $taxonomy->get();

        return $taxonomy;
    }
}
