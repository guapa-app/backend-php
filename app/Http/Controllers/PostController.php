<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\PostRepositoryInterface;
use App\Http\Requests\PostRequest;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $postService;
    private $postRepository;

    public function __construct(PostService $postService, PostRepositoryInterface $postRepository)
    {
        $this->postService = $postService;
        $this->postRepository = $postRepository;
    }

    public function index()
    {
        $posts = $this->postRepository->getAllPaginated();

        return view('blogs', compact('posts'));
    }

    public function show($id)
    {
        $post = $this->postRepository->getOneWithRelations($id);

        return view('single-blog', compact('post'));
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
