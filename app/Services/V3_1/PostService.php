<?php

namespace App\Services\V3_1;

use App\Contracts\Repositories\CommentRepositoryInterface;
use App\Contracts\Repositories\PostRepositoryInterface;
use App\Enums\PostType;
use App\Models\Media;
use App\Models\Post;
use App\Models\UserVote;
use App\Services\MediaService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
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

        if ($post->type === PostType::Vote->value) {
            foreach ($data['vote_options'] as $optionText) {
                $post->voteOptions()->create([
                    'option' => $optionText,
                    'votes_count' => 0
                ]);
            }
        }

        $post = $this->updateMedia($post, $data);

        $post->load('media', 'voteOptions', 'user', 'category', 'product');

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

        //Update existing options and create new ones
        if ($post->type === PostType::Vote->value) {
            $this->updateVoteOptions($post, $data);
        }


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

    public function vote(Post $post, int $optionId): void
    {
        $option = $post->voteOptions()->where('id', $optionId)->firstOrFail();

        try {
             DB::transaction(function () use ($post, $option) {
                 $postVote = UserVote::where('post_id', $post->id)
                     ->where('user_id', auth()->id())
                     ->first();

                 $previousOptionId = $postVote ? $postVote->vote_option_id : null;


                 $postVote = UserVote::updateOrCreate(
                    [
                        'post_id' => $post->id,
                        'user_id' => auth()->id(),
                    ],
                    [
                        'vote_option_id' => $option->id
                    ]
                );

                // If this was an update (not a new vote)
                if (!$postVote->wasRecentlyCreated) {
                    // Only update counts if the vote option changed
                    if ($previousOptionId && $previousOptionId !== $option->id) {
                        // Decrement previous option count
                        $post->voteOptions()->where('id', $previousOptionId)
                            ->decrement('votes_count');
                        // Increment new option count
                        $option->increment('votes_count');
                    }
                }else{
                    $option->increment('votes_count');
                }
                $post->load('voteOptions');
             });
        } catch (\Exception $e) {
            throw new \Exception('Failed to vote'. $e->getMessage());
        }
    }
    private function updateVoteOptions(Post $post, array $data): void
    {
        $options = $data['vote_options'] ?? [];
        $existingOptions = $post->voteOptions()->pluck('id', 'option')->toArray();

        // Create or update options
        foreach ($options as $option) {
            if (isset($option['id'])) {
                // Update existing option
                $existingOption = $post->voteOptions()->where('id', $option['id'])->first();
                if ($existingOption->option !== $option['option']) {
                    $existingOption->update([
                        'option' => $option['option']
                    ]);
                }

                unset($existingOptions[$option['option']]);
            } else {
                // Create new option
                $post->voteOptions()->create([
                    'option' => $option['option'],
                    'votes_count' => 0
                ]);
            }
        }

        // Delete options that are not in the provided data
        if (!empty($existingOptions)) {
            $post->voteOptions()->whereIn('id', array_values($existingOptions))->delete();
        }
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

        $mediaCollections = [
            'media_ids' => 'posts',
            'before_media_ids' => 'before',
            'after_media_ids' => 'after'
        ];

        foreach ($mediaCollections as $mediaKey => $collectionName) {
            if (!empty($data[$mediaKey])) {
                Media::whereIn('id', $data[$mediaKey])
                    ->update([
                        'model_type' => 'post',
                        'model_id' => $post->id,
                        'collection_name' => $collectionName
                    ]);
            }
        }

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
