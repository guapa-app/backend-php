<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\PostRepositoryInterface;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Taxonomy;
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
            'perPage' => 12,
            'order' => 'desc',
            'type' => 'blog',
            'status' => '1',
        ]);

        $posts = $this->postRepository
            ->all($request)
            ->appends($request->query());

        $currentPage = $posts->currentPage();
        $lastPage = $posts->lastPage();
        $start = max($currentPage - 1, 1);
        $end = min($currentPage + 1, $lastPage);
        $postsCounter = $posts->total();

        $postCategories = Taxonomy::query()
            ->where('type', 'blog_category')
            ->whereHas('posts')
            ->withCount('posts')
            ->get();

        $postTags = Tag::query()
            ->whereHas('posts')
            ->get();

        return view('frontend.blogs', compact(
            'posts',
            'postsCounter',
            'currentPage',
            'lastPage',
            'start',
            'end',
            'postTags',
            'postCategories',
            'request',
        ));
    }

    public function show($id)
    {
        $post = $this->postRepository->getOneWithRelations($id);

        return view('frontend.single-blog', compact('post'));
    }
}
