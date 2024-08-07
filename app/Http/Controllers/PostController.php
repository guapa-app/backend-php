<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\PostRepositoryInterface;
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

    public function index(Request $request)
    {
        $request->merge([
            'perPage' => 9,
            'order' => 'desc',
        ]);

        $posts = $this->postRepository->all($request);
        $currentPage = $posts->currentPage();
        $lastPage = $posts->lastPage();
        $start = max($currentPage - 1, 1);
        $end = min($currentPage + 1, $lastPage);

        return view('frontend.blogs', compact('posts', 'currentPage', 'lastPage', 'start', 'end'));
    }

    public function show($id)
    {
        $post = $this->postRepository->getOneWithRelations($id);

        return view('frontend.single-blog', compact('post'));
    }
}
