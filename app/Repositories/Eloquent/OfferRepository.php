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
     * @param Offer $model
     */
    public function __construct(Offer $model)
    {
        parent::__construct($model);
    }

    public function all(Request $request): object
    {
        $query = Product::query()
            ->applyFilters($request)
            ->applyOrderBy($request->get('sort'), $request->get('order'))
            ->withListRelations($request)
            ->withListCounts($request)
            ->when(!$this->isAdmin(), function ($query) use ($request) {
                $query->withApiListRelations($request);
            });

        if ('enduser' === strtolower($request->header('X-App-Type'))) {
            $query->whereHas('offer');
        } else {
            if (empty($request->vendor_id)) {
                $request->merge([
                    'vendor_id' => $this->user->managerVendorId(),
                ]);
            }

            $query->withAllVendorOffers($request);
        }

        if ($request->has('perPage')) {
            $perPage = (int) ($request->has('perPage') ? $request->get('perPage') : $this->perPage);

            if ($perPage > 50) {
                $perPage = 50;
            }

            return $query->paginate($perPage);
        } else {
            return $query->get();
        }
    }
}
