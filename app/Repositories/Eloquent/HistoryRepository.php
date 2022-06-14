<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\HistoryRepositoryInterface;
use App\Models\History;

/**
 * History repository
 */
class HistoryRepository extends EloquentRepository implements HistoryRepositoryInterface
{
	/**
	 * Items per page for pagination
	 * @var integer
	 */
	public $perPage = 10;

	/**
	 * Construct an instance of the repo
	 * 
	 * @param \App\Models\History $model
	 */
	public function __construct(History $model)
	{
		parent::__construct($model);
	}
}
