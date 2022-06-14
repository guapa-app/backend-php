<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\Repositories\OfferRepositoryInterface;
use App\Models\Offer;

/**
 * Offer repository
 */
class OfferRepository extends EloquentRepository implements OfferRepositoryInterface
{
	/**
	 * Items per page for pagination
	 * @var integer
	 */
	public $perPage = 10;

	/**
	 * Construct an instance of the repo
	 * 
	 * @param \App\Models\Offer $model
	 */
	public function __construct(Offer $model)
	{
		parent::__construct($model);
	}
}
