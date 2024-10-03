<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

/**
 * Product repository.
 */
class ProductRepository extends EloquentRepository implements ProductRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     *
     * @param Product $model
     */
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * Get multiple rows from database.
     * @param Request $request
     * @return LengthAwarePaginator| Collection
     */
    public function all(Request $request): object
    {
        $perPage = (int)($request->has('perPage') ? $request->get('perPage') : $this->perPage);

        if ($perPage > 50) {
            $perPage = 50;
        }

        $query = $this->model->applyFilters($request);

        $order = $request->get('order');
        if (Schema::hasColumn('products', 'sort_order')) {
            $sortColumn = ($request->get('sort') ?? 'sort_order');
            $order = 'asc';
        } else {
            $sortColumn = $request->get('sort');
        }

        $query->applyOrderBy($sortColumn, $order);

        $query->withListRelations($request)
            ->withListCounts($request)
            ->when(!$this->isAdmin(), function ($query) use ($request) {
                $query->withApiListRelations($request);
            });

        if ($request->has('perPage')) {
            return $query->paginate($perPage);
        } else {
            return $query->get();
        }
    }
}
