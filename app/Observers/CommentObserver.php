<?php

namespace App\Observers;

use App\Models\Comment;
use App\Notifications\NewCommentNotification;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        $post = $comment->post;
        $post->user->notify(new NewCommentNotification($comment));
    }
}
