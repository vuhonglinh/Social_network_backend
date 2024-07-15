<?php

namespace App\Events;

use App\Http\Resources\PostLikeResourceCollection;
use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostLikeEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;

    /**
     * Create a new event instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('like.post'), // Specific channel for the post
        ];
    }

    public function broadcastWith(): array
    {
        // Assuming you have loaded the likes and users relationships in the Post model
        $likes = $this->post->likes()->with('user')->get();

        return [
            'data' => new PostLikeResourceCollection($likes, $this->post)
        ];
    }
}
