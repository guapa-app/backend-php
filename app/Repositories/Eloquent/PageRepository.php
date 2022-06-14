<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\Repositories\PageRepositoryInterface;
use App\Models\Page;

/**
 * Page repository
 */
class PageRepository extends EloquentRepository implements PageRepositoryInterface
{
	/**
	 * Items per page for pagination
	 * @var integer
	 */
	public $perPage = 10;

	/**
	 * Construct an instance of the repo
	 * @param \App\Models\Page $model
	 */
	public function __construct(Page $model)
	{
		parent::__construct($model);
	}

	/**
	 * Get all pages
	 * 
	 * @param  \Illuminate\Http\Request $request
	 * 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll() : Collection
	{
		return cache()->get('pages', function() {
			return $this->model->all();
		});
	}
}
