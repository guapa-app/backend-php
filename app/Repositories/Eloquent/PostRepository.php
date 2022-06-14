<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\PostRepositoryInterface;
use App\Models\Post;

/**
 * Post repository
 */
class PostRepository extends EloquentRepository implements PostRepositoryInterface
{
	/**
	 * Items per page for pagination
	 * @var integer
	 */
	public $perPage = 10;

	/**
	 * Construct an instance of the repo
	 * 
	 * @param \App\Models\Post $model
	 */
	public function __construct(Post $model)
	{
		parent::__construct($model);
	}
}
