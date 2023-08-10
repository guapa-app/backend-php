<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\CommentRepositoryInterface;
use App\Exceptions\NotAllowedException;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @group Community
 */
class CommentController extends BaseApiController
{
    private $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        parent::__construct();

        $this->commentRepository = $commentRepository;
    }

    /**
     * Comments list
     *
     * @unauthenticated
     *
     * @responseFile 200 responses/posts/list-comments.json
     *
     * @queryParam post_id integer Get Comments for specific post. Example: 1
     * @queryParam page number for pagination Example: 1
     * @queryParam perPage Results to fetch per page Example: 15
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request)
    {
        return $this->commentRepository->all($request);
    }

    /**
     * Create comment
     *
     * @responseFile 200 responses/posts/create-comment.json
     * @responseFile 404 scenario="Post not found" responses/errors/404.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     *
     * @param CommentRequest $request
     * @return Model
     */
    public function create(CommentRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['user_type'] = auth()->user()->getMorphClass();
        return $this->commentRepository->create($data);
    }

    /**
     * Update comment
     *
     * @urlParam id integer required Comment id. Example: 10
     *
     * @responseFile 200 responses/posts/create-comment.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 404 scenario="Comment not found" responses/errors/404.json
     * @responseFile 403 scenario="Unauthorized to update comment" responses/errors/403.json
     *
     * @param Request\CommentRequest $request
     * @param Comment $comment
     * @return Model
     */
    public function update(CommentRequest $request, $id)
    {
        $comment = $this->commentRepository->getOneOrFail($id);

        if (!$this->user->can('update', $comment)) {
            throw new NotAllowedException();
        }

        $data = $request->validated();
        unset($data['post_id']);
        return $this->commentRepository->update($comment, $data);
    }

    /**
     * Delete comment
     *
     * @urlParam id integer required Comment id. Example: 10
     *
     * @responseFile 200 responses/posts/delete-comment.json
     * @responseFile 404 scenario="Comment not found" responses/errors/404.json
     * @responseFile 403 scenario="Unauthorized to delete comment" responses/errors/403.json
     *
     * @param Comment $comment
     * @return array|int[]
     */
    public function delete($id)
    {
        $comment = $this->commentRepository->getOneOrFail($id);

        if (!$this->user->can('delete', $comment)) {
            throw new NotAllowedException();
        }

        return $this->commentRepository->delete($comment->id);
    }
}
