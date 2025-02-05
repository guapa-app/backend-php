<?php

namespace App\Notifications;

use App\Channels\FirebaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewLikeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * User object.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * Post object.
     *
     * @var \App\Models\Post
     */
    public $post;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function __construct($user, $post)
    {
        $this->user = $user;
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', FirebaseChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->post->id,
            'summary' => $this->getSummary(),
            'type' => 'community',
            'title' => 'إعجاب جديد',
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
            'title' => 'إعجاب جديد',
            'body' => $this->getSummary(),
        ];
    }

    /**
     * Get the summary of the like.
     *
     * @return string
     */
    public function getSummary()
    {
        return " قام {$this->user->name} بالإعجاب بمنشورك ";
    }
}
