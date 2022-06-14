<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Contracts\Repositories\CommentRepositoryInterface;
use App\Models\Comment;

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
     * @queryParam page Page number for pagination Example: 1
     * @queryParam perPage Results to fetch per page Example: 15
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function index(Request $request)
	{
        $comments = $this->commentRepository->all($request);
        return response()->json($comments);
	}

    /**
     * Create comment
     * 
     * @responseFile 200 responses/posts/create-comment.json
     * @responseFile 404 scenario="Post not found" responses/errors/404.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * 
     * @param  \App\Http\Requests\CommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function create(CommentRequest $request)
	{
        $data = $request->validated();
        $data['user_id' ] = auth()->id();
        $data['user_type'] = auth()->user()->getMorphClass();
        $comment = $this->commentRepository->create($data);
        return response()->json($comment);
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
     * @param  \Illuminate\Http\Request\CommentRequest $request
     * @param  Comment        $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CommentRequest $request, $id)
    {
        $comment = $this->commentRepository->getOneOrFail($id);
        
        if ( ! $this->user->can('update', $comment)) {
            abort(403, 'You can\'t update this comment');
        }

        $data = $request->validated();
        unset($data['post_id']);
        $comment = $this->commentRepository->update($comment, $data);
        return response()->json($comment);
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
     * @param  \App\Models\Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $comment = $this->commentRepository->getOneOrFail($id);

        if ( ! $this->user->can('delete', $comment)) {
            abort(403, 'You can\'t delete this comment');
        }

        $ids = $this->commentRepository->delete($comment->id);
        return response()->json($ids);
    }
}
