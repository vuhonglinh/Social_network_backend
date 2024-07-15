<?php

namespace App\Notifications;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PostNotification extends Notification implements ShouldBroadcast
{
    // use Queueable;

    /**
     * Create a new notification instance.
     */
    public $post;
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable)
    {
        return new PostResource($this->post);
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            new PostResource($this->post)
        ]);
    }

    public function broadcastType(): string //Để client phân biệt giữa các thông báo với nhau
    {
        return 'broadcast.message';
    }
}