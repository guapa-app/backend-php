<?php

namespace App\Contracts\Repositories\V3_1;

use App\Contracts\Repositories\TaxRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Taxonomy Repository Interface.
 */
interface TaxonomyRepositoryInterface extends TaxRepositoryInterface
{
    /**
     * Get categories list for api common data.
     *
     * @return Collection|LengthAwarePaginator
     */
    public function getData(
        array $with = [],
        array $where = [],
        bool $isPaginated = false
    ): Collection|LengthAwarePaginator;
}
