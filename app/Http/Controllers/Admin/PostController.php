<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Requests\PostRequest;
use App\Services\PostService;
use App\Contracts\Repositories\PostRepositoryInterface;

class PostController extends BaseAdminController
{
    private $postService;
    private $postRepository;

    public function __construct(PostService $postService, PostRepositoryInterface $postRepository)
    {
        parent::__construct();
        
        $this->postService = $postService;
        $this->postRepository = $postRepository;
    }
    
	public function index(Request $request)
	{
        $posts = $this->postRepository->all($request);
        return response()->json($posts);
	}

	public function single($id = 0)
	{
		$post = $this->postRepository->getOneWithRelations($id);
        return response()->json($post);
	}

	public function create(PostRequest $request)
	{
        $data = $request->validated();
        $data['admin_id'] = auth()->id();
        $post = $this->postService->create($data);
        return response()->json($post);
	}

    public function update(PostRequest $request, $id = 0)
    {
        $post = $this->postService->update($id, $request->validated());
        return response()->json($post);
    }

    public function delete($id = 0)
    {
        $ids = $this->postRepository->delete($id);
        return response()->json($ids);
    }
}
