<?php

namespace App\Services;

use App\Contracts\Repositories\CommentRepositoryInterface;
use App\Contracts\Repositories\PostRepositoryInterface;
use App\Models\Comment;
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
	 * @param  \App\Models\Post $post
	 * @param  array 			$data
	 * @return \App\Models\Post
	 */
	public function updateMedia(Post $post, array $data): Post
	{
		// New media must be specified or old media to keep
		// without deletion
		if ( ! isset($data['media']) && ! isset($data['keep_media'])) {
			return $post;
		}

		// If no keep_media array is provided
		// We will remove all old media
		$keep_media = [0];
		if (isset($data['keep_media']) && ! empty($data['keep_media'])) {
			$keep_media = $data['keep_media'];
		}

		// Remove media user doesn't want to keep
		$post->media()->whereNotIn('id', $keep_media)->delete();

		// Check for new media
		if ( ! isset($data['media']) || ! is_array($data['media'])) {
			return $post;
		}	

		foreach ($data['media'] as $key => $value) {
			if ($value instanceof UploadedFile) {
				$post->addMedia($value)->toMediaCollection('posts');
			}
		}

		$post->load('media');

		return $post;
	}
}