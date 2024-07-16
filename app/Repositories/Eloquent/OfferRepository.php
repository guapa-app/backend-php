<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\OfferRepositoryInterface;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Offer repository.
 */
class OfferRepository extends EloquentRepository implements OfferRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     *
     * @param \App\Models\Offer $model
     */
    public function __construct(Offer $model)
    {
        parent::__construct($model);
    }

    public function all(Request $request): object
    {
        if (empty($request->vendor_id))
            $request->merge([
                'vendor_id' => $this->user->managerVendorId(),
            ]);

        $query = Product::query()
            ->applyFilters($request)
            ->applyOrderBy($request->get('sort'), $request->get('order'))
            ->withListRelations($request)
            ->withListCounts($request)
            ->withApiListRelationsForVendor($request);

        if ($request->has('perPage')) {
            return $query->paginate($request->perPage);
        } else {
            return $query->get();
        }
    }
}
