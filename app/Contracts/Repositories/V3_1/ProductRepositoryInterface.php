<?php

namespace App\Contracts\Repositories\V3_1;

use Illuminate\Database\Eloquent\Collection;

/**
 * Product Repository Interface.
 */
interface ProductRepositoryInterface
{
    public function getData(string $with = "", int $limit = null): Collection;
}
