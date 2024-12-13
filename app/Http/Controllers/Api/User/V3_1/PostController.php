<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\PostRepositoryInterface;
use App\Enums\PostType;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\User\PostRequest;
use App\Http\Resources\User\V3_1\PostCollection;
use App\Http\Resources\User\V3_1\PostResource;
use App\Models\Post;
use App\Services\V3_1\PostService;
use Illuminate\Http\Request;

class PostController extends BaseApiController
{
    protected $postRepository;
    protected $postService;

    public function __construct(PostRepositoryInterface $postRepository, PostService $postService)
    {
        parent::__construct();

        $this->postRepository = $postRepository;
        $this->postService = $postService;
    }
    public function index(Request $request)
    {
        if (!$request->has('user_id')) {
            $request->merge(['status' => '1']);
        }
        $posts =  $this->postRepository->all($request);
        return PostCollection::make($posts)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single($id)
    {
        $post = $this->postRepository->getOneWithRelations((int) $id);

        $post->content = strip_tags($post->content);

        return PostResource::make($post)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['status'] = 3;
        $post = $this->postService->create($data);

        return PostResource::make($post)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function update(PostRequest $request, $id)
    {
        $data = $request->validated();
        $post = $this->postService->update((int) $id, $data);

        return PostResource::make($post)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function delete($id)
    {
        $this->postService->delete((int) $id);

        return $this->successJsonRes([], __('api.deleted'));
    }

    public function vote(Request $request, Post $post)
    {
        $request->validate([
            'option_id' => 'required|exists:vote_options,id',
        ]);

        if ($post->type != PostType::Vote->value) {
            return $this->errorJsonRes([],__('This post is not a vote post'));
        }
        $this->postService->vote($post, $request->option_id);

        return PostResource::make($post)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
