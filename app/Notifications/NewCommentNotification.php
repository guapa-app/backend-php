<?php

namespace App\Notifications;

use App\Channels\FirebaseChannel;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Comment object.
     *
     * @var Comment
     */
    public $comment;

    /**
     * Create a new notification instance.
     *
     * @param Comment $comment
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', FirebaseChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->comment->id,
            'summary' => $this->getSummary(),
            'type' => 'community',
            'title' => 'تعليق جديد',
        ];
    }

    /**
     * Get the Firebase representation of the notification.
     *
     * @return array
     */
    public function toFirebase()
    {
        return [
            'title' => 'تعليق جديد',
            'body' => $this->getSummary(),
        ];
    }

    /**
     * Get the summary of the comment.
     *
     * @return string
     */
    public function getSummary()
    {
        return " تم إضافة تعليق جديد على منشورك من قبل  {$this->comment->user->name} ";
    }
}
