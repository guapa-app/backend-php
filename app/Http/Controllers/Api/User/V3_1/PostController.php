<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\PostRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\User\V3_1\PostCollection;
use App\Http\Resources\User\V3_1\PostResource;
use Illuminate\Http\Request;

class PostController extends BaseApiController
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        parent::__construct();

        $this->postRepository = $postRepository;
    }
    public function index(Request $request)
    {
        $request->merge(['status' => '1']);
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
}
