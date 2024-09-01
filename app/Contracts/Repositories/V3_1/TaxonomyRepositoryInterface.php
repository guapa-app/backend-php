<?php

namespace App\Contracts\Repositories\V3_1;

use Illuminate\Database\Eloquent\Collection;

/**
 * Taxonomy Repository Interface.
 */
interface TaxonomyRepositoryInterface
{
    /**
     * Get categories list for api common data.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getData(
        array $where = [],
        array $with = [],
        int $limit = null
    ): Collection;
}
