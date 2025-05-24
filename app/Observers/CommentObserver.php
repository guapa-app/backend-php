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
        $user = $post->user;

        // Generate comment summary
        $summary = "تم إضافة تعليق جديد على منشورك من قبل {$comment->user->name}";

        // Send notification via unified service
        app(\App\Services\UnifiedNotificationService::class)->send(
            module: 'comments',
            title: 'New Comment',
            summary: $summary,
            recipientId: $user->id,
            data: [
                'comment_id' => $comment->id,
                'post_id' => $post->id,
            ]
        );
    }
}
