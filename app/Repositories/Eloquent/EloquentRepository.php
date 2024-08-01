<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\EloquentRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

/**
 * The base class for all eloquent repositories.
 */
class EloquentRepository implements EloquentRepositoryInterface
{
    /**
     * Current logged-in user instance.
     * @var User
     */
    protected $user;

    /**
     * An empty instance of the model related to this repository.
     * @var User
     */
    protected $model;

    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->setCurrentUser();
    }

    /**
     * Get multiple rows from database.
     * @param Request $request
     * @return LengthAwarePaginator| Collection
     */
    public function all(Request $request, $perPage = 10): object
    {
        $perPage = (int) ($request->has('perPage') ? $request->get('perPage') : $this->perPage);

        if ($perPage > 50) {
            $perPage = 50;
        }

        $query = $this->model
            ->applyFilters($request)
            ->applyOrderBy($request->get('sort'), $request->get('order'))
            ->withListRelations($request)
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

    /**
     * Get all records.
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Get single row from database.
     *
     * @param int $id
     * @param array $where
     *
     * @return Model|null
     */
    public function getOne(int $id, $where = []): ?Model
    {
        if ($id > 0) {
            $where['id'] = $id;
        }

        return $this->model->where($where)->first();
    }

    /**
     * Get first row from database.
     *
     * @param array $where
     *
     * @return Model|null
     */
    public function getFirst($where = []): ?Model
    {
        return $this->getOne(0, $where);
    }

    /**
     * Get single row from database with relations.
     *
     * @param int $id
     * @param array $where
     *
     * @return Model|null
     */
    public function getOneWithRelations(int $id, $where = []): ?Model
    {
        if ($id > 0) {
            $where['id'] = $id;
        }

        return $this->model->where($where)->withSingleRelations()->firstOrFail();
    }

    /**
     * Get single row from database or fail with 404.
     * @param int $id
     * @param array $where
     * @return Model
     * @throws ModelNotFoundException
     */
    public function getOneOrFail(int $id, $where = []): Model
    {
        $where['id'] = $id;

        return $this->model->where($where)->firstOrFail();
    }

    public function getMany($ids, $where = [], $relations = [])
    {
        $ids = $this->resolveIds($ids);

        return $this->model->whereIn('id', $ids)
            ->where($where)->with($relations)->get();
    }

    /**
     * Create new model and persist in db.
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update model.
     * @param mixed $model
     * @param array $data
     * @param array $where
     * @return Model
     * @throws ModelNotFoundException
     */
    public function update($model, array $data, $where = []): Model
    {
        $model = $this->isModel($model) ? $model : $this->getOneOrFail($model, $where);
        $model->update($data);

        return $model;
    }

    /**
     * Delete by ids.
     * @param int|array|json $id
     * @return void
     */
    public function delete($id, $where = []): array
    {
        $ids = $this->resolveIds($id);
        // If no ids are specified and no where conditions provided
        // We cannot allow this deletes operation
        if (empty($ids) && empty($where)) {
            return [];
        }

        $this->model->when(!empty($ids), function ($query) use ($ids) {
            $query->whereIn('id', $ids);
        })->where($where)->delete();

        return $ids;
    }

    /**
     * Set current user based on api or admin.
     * @return void
     */
    public function setCurrentUser(): void
    {
        $route = request()->route();
        if (!$route) {
            $this->user = auth()->user();

            return;
        }

        $action = $route->getAction();
        $is_admin = isset($action['prefix']) &&
            strpos($action['prefix'], 'admin-api') === 0;
        $guard = $is_admin ? 'admin' : 'api';

        $this->user = auth($guard)->user();
    }

    /**
     * Check if the current user is an admin.
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->user && $this->user->isAdmin();
    }

    /**
     * Pick some keys from array.
     * @param array $data
     * @param array $keys
     * @return array
     */
    public function only(array $data, array $keys): array
    {
        return Arr::only($data, $keys);
    }

    /**
     * Check if variable is a Model.
     * @param mixed $variable
     * @return bool
     */
    public function isModel($variable): bool
    {
        return $variable instanceof Model;
    }

    /**
     * Get array of ids from input.
     * @param int|json|array $id
     * @return array
     */
    public function resolveIds($id): array
    {
        if (is_numeric($id)) {
            return [(int) $id];
        }

        if (is_array($id)) {
            return $id;
        }

        try {
            return (array) json_decode(urldecode($id));
        } catch (Exception $e) {
            // Not valid json
            return [];
        }
    }

    /**
     * Get all records paginated.
     * @param int $perPage
     * @return EloquentRepository
     */
    public function getAllPaginated(int $perPage = 10)
    {
        return $this->model = $this->model->paginate($perPage);
    }
}
