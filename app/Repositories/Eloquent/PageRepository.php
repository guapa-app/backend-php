<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\PageRepositoryInterface;
use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;

/**
 * Page repository.
 */
class PageRepository extends EloquentRepository implements PageRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     * @param \App\Models\Page $model
     */
    public function __construct(Page $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all pages.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll() : Collection
    {
        return cache()->get('pages', function () {
            return $this->model->all();
        });
    }
}
