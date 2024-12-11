<?php

namespace App\Services\V3_1;

use App\Contracts\Repositories\CommentRepositoryInterface;
use App\Contracts\Repositories\PostRepositoryInterface;
use App\Models\Post;
use App\Services\MediaService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PostService
{
    private $postRepository;


    public function __construct(PostRepositoryInterface $postRepository) {
        $this->postRepository = $postRepository;
    }

    public function create(array $data)
    {
        $post = $this->postRepository->create($data);

        $post = $this->updateMedia($post, $data);

        return $post;
    }

    public function update($id, array $data)
    {
        // check if the user is the owner of the post
        $post = $this->postRepository->getOneOrFail($id);

        if ($post->user_id !== auth()->id()) {
            abort(403, 'You are not allowed to update this post');
        }

        if ($this->hasContentOrImagesUpdated($post, $data)) {
            $data['status'] = 3;
        }
        $post = $this->postRepository->update($id, $data);

        $post = $this->updateMedia($post, $data);

        return $post;
    }

    public function delete($id)
    {
        // check if the user is the owner of the post
        $post = $this->postRepository->getOneOrFail($id);
        if ($post->user_id !== auth()->id()) {
            abort(403, 'You are not allowed to delete this post');
        }
        $this->postRepository->delete($id);
    }

    /**
     * Update post media.
     * @param Post $post
     * @param array $data
     * @return Post
     */
    public function updateMedia(Post $post, array $data): Post
    {
        $keep_media = $data['keep_media'] ?? [];
        $post->media()->whereNotIn('id', $keep_media)->delete();

        $this->addMediaToPost($post, $data['before_images'] ?? [], 'before');
        $this->addMediaToPost($post, $data['after_images'] ?? [], 'after');
        $this->addMediaToPost($post, $data['media'] ?? []);

        $post->load('media');

        return $post;
    }

    private function addMediaToPost(Post $post, array $media, string $collection = 'posts'): void
    {
        foreach ($media as $value) {
            if ($value instanceof UploadedFile) {
                $post->addMedia($value)->toMediaCollection($collection);
            } elseif (is_string($value) && Str::startsWith($value, 'data:')) {
                $post->addMediaFromBase64($value)->toMediaCollection($collection);
            }
        }
    }

    private function hasContentOrImagesUpdated(Post $post, array $data): bool
    {
        $contentUpdated = isset($data['content']) && $data['content'] !== $post->content;
        $imagesUpdated = isset($data['media']) || isset($data['before_images']) || isset($data['after_images']);

        return $contentUpdated || $imagesUpdated;
    }
}
