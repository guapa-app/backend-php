<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\TaxRepositoryInterface;
use App\Models\Taxonomy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Taxonomy Repository.
 */
class TaxRepository extends EloquentRepository implements TaxRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     * @param \App\Models\Taxonomy $model
     */
    public function __construct(Taxonomy $model)
    {
        parent::__construct($model);
    }

    /**
     * Update taxonomy.
     *
     * @param  mixed $taxonomy
     * @param  array  $data
     * @return \App\Models\Taxonomy
     */
    public function update($taxonomy, array $data, $where = []) : Model
    {
        // Unset parent if type is not category
        if ((isset($data['type']) && $data['type'] !== 'category') ||
            !isset($data['parent_id'])) {
            $data['parent_id'] = null;
        }

        // Update taxonomy
        return parent::update($taxonomy, $data);
    }

    /**
     * Get categories list for api common data.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getForApiData(array $where = []) : Collection
    {
        return Taxonomy::isRoot()->with([
            'children', 'icon', 'children.icon',
        ])->where($where)->get();
    }

    /**
     * Get root parent of taxonomy.
     *
     * @param  \App\Models\Taxonomy|int  $taxonomy
     * @return \App\Models\Taxonomy|null
     */
    public function getRoot($taxonomy) : ?Taxonomy
    {
        if (!$taxonomy instanceof Taxonomy &&
            !$taxonomy = $this->getOne($taxonomy)) {
            return null;
        }

        if ($taxonomy->parent_id == null) {
            return $taxonomy;
        } else {
            return $taxonomy->ancestors()->isRoot()->first();
        }
    }


    public function all(Request $request): object
    {
        $perPage = (int) ($request->has('perPage') ? $request->get('perPage') : $this->perPage);

        if ($perPage > 50) {
            $perPage = 50;
        }

        $query = $this->model->applyFilters($request);

        $order = $request->get('order');
        if (Schema::hasColumn('taxonomies', 'sort_order')) {
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

        if (Schema::hasColumn('taxonomies', 'is_published')) {
            $query->where('is_published', true);
        }

        if ($request->has('perPage')) {
            return $query->paginate($perPage);
        } else {
            return $query->get();
        }
    }
}
