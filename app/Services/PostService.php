<?php

namespace App\Services;

use App\Contracts\Repositories\CommentRepositoryInterface;
use App\Contracts\Repositories\PostRepositoryInterface;
use App\Models\Post;
use Illuminate\Http\UploadedFile;

class PostService
{
    private $postRepository;

    private $commentRepository;

    public function __construct(PostRepositoryInterface $postRepository,
        CommentRepositoryInterface $commentRepository)
    {
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }

    public function create(array $data)
    {
        $post = $this->postRepository->create($data);

        $post = $this->updateMedia($post, $data);

        return $post;
    }

    public function update($id, array $data)
    {
        $post = $this->postRepository->update($id, $data);

        $post = $this->updateMedia($post, $data);

        return $post;
    }

    /**
     * Update post media
     * @param Post $post
     * @param array $data
     * @return Post
     */
    public function updateMedia(Post $post, array $data): Post
    {
        (new MediaService())->handleMedia($post, $data);

        $post->load('media');

        return $post;
    }
}
