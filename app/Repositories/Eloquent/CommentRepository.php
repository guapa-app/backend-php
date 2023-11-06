<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\CommentRepositoryInterface;
use App\Models\Comment;

/**
 * Comment repository.
 */
class CommentRepository extends EloquentRepository implements CommentRepositoryInterface
{
    /**
     * Items per page for pagination.
     * @var int
     */
    public $perPage = 10;

    /**
     * Construct an instance of the repo.
     *
     * @param \App\Models\Comment $model
     */
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }
}
