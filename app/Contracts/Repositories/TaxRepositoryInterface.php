<?php

namespace App\Contracts\Repositories;

use App\Models\Taxonomy;
use Illuminate\Database\Eloquent\Collection;

/**
 * Taxonomy Repository Interface.
 */
interface TaxRepositoryInterface
{
    /**
     * Get categories list for api common data.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getForApiData(array $where = []) : Collection;

    /**
     * Get root parent of taxonomy.
     * @param  \App\Models\Taxonomy|int  $taxonomy
     * @return \App\Models\Taxonomy|null
     */
    public function getRoot($taxonomy) : ?Taxonomy;
}
