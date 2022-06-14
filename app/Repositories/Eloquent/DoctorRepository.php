<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\Repositories\DoctorRepositoryInterface;
use App\Helpers\Common;
use App\Models\Doctor;

/**
 * Doctor Repository
 */
class DoctorRepository extends EloquentRepository implements DoctorRepositoryInterface
{
	/**
	 * Items per page for pagination
	 * @var integer
	 */
	public $perPage = 10;

	/**
	 * Construct an instance of the repo
	 * 
	 * @param \App\Models\Doctor $model
	 */
	public function __construct(Doctor $model)
	{
		parent::__construct($model);
	}
}
