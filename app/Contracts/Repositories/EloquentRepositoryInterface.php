<?php

namespace App\Contracts\Repositories;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * The base interface for all eloquent repositories
 */
interface EloquentRepositoryInterface
{
	/**
	 * Get multiple rows from database
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Pagination\LengthAwarePaginator
	 *         | \Illuminate\Database\Eloquent\Collection
	 */
	public function all(Request $request);

	/**
	 * Get single row from database
	 * @param  int    $id
	 * @param  array  $where
	 * @return null|\Illuminate\Database\Eloquent\Model
	 */
	public function getOne(int $id, $where = []): ?Model;

	/**
	 * Get first row from database
	 * @param  array  $where
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function getFirst($where = []): ?Model;

	/**
	 * Get single row from database or fail with 404
	 * @param  int    $id
	 * @param  array  $where
	 * @return \Illuminate\Database\Eloquent\Model
	 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
	 */
	public function getOneOrFail(int $id, $where = []): Model;

	/**
	 * Create new model and persist in db
	 * @param  array  $data
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function create(array $data): Model;

	/**
	 * Update model
	 * @param  mixed  $model
	 * @param  array  $data
	 * @param  array  $where
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function update($model, array $data, $where = []): Model;

	/**
	 * Delete models by ids
	 * @param  int|array $id
	 * @param  array $where
	 * @return void
	 */
	public function delete($id, $where = []): array;

	/**
	 * Is the current user an admin
	 * @return boolean
	 */
	public function isAdmin() : bool;

	/**
	 * Set logged in user
	 */
	public function setCurrentUser(): void;

	/**
	 * Pick some keys from array
	 * @param  array  $data
	 * @param  array  $keys
	 * @return array
	 */
	public function only(array $data, array $keys): array;
}
