<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Helpers\Common;
use App\Models\Vendor;

/**
 * Vendor repository
 */
class VendorRepository extends EloquentRepository implements VendorRepositoryInterface
{
	/**
	 * Items per page for pagination
	 * @var integer
	 */
	public $perPage = 10;

	/**
	 * Construct an instance of the repo
	 * 
	 * @param \App\Models\Vendor $model
	 */
	public function __construct(Vendor $model)
	{
		parent::__construct($model);
	}
}
