<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\PostRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @group Community
 */
class PostController extends BaseApiController
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        parent::__construct();

        $this->postRepository = $postRepository;
    }

    /**
     * Posts list
     *
     * @unauthenticated
     *
     * @responseFile 200 responses/posts/list.json
     *
     * @queryParam category_id integer Get Posts for specific user. Example: 1
     * @queryParam page number for pagination Example: 1
     * @queryParam perPage Results to fetch per page Example: 15
     * @queryParam keyword Search posts. Example: Liver
     * @queryParam most_viewed Order posts by views.
     * @queryParam most_liked Order posts by likes.
     * @queryParam sort Field used to sort results (created_at). Example: created_at
     * @queryParam order The sort order (DESC, ASC). Example: DESC
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request)
    {
        return $this->postRepository->all($request);
    }

    /**
     * Get post by id
     *
     * @unauthenticated
     *
     * @responseFile 200 responses/posts/details.json
     * @responseFile 404 scenario="Post not found" responses/errors/404.json
     *
     * @urlParam id required Post id. Example: 5
     *
     * @param int $id
     * @return Model|null
     */
    public function single($id)
    {
        $post = $this->postRepository->getOneWithRelations((int)$id);

        $post->content = strip_tags($post->content);

        return $post;
    }
}
