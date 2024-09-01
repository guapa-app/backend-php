<?php

namespace App\Contracts\Repositories\V3_1;

use Illuminate\Database\Eloquent\Collection;

/**
 * Offer Repository Interface.
 */
interface OfferRepositoryInterface
{
    public function getData(int $limit = null): Collection;
}
