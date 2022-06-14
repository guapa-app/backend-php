<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Models\Review;

/**
 * Review repository
 */
class ReviewRepository extends EloquentRepository implements ReviewRepositoryInterface
{
	/**
	 * Items per page for pagination
	 * @var integer
	 */
	public $perPage = 10;

	/**
	 * Construct an instance of the repo
	 * 
	 * @param \App\Models\Review $model
	 */
	public function __construct(Review $model)
	{
		parent::__construct($model);
	}
}
