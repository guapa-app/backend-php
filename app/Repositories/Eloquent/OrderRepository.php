<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Repositories\Eloquent\EloquentRepository;
use App\Models\Order;

/**
 * Order repository
 */
class OrderRepository extends EloquentRepository implements OrderRepositoryInterface
{
	/**
	 * Items per page for pagination
	 * @var integer
	 */
	public $perPage = 10;

	/**
	 * Construct an instance of the repo
	 * @param \App\Models\Order $model
	 */
	public function __construct(Order $model)
	{
		parent::__construct($model);
	}
}
