<?php

namespace App\Events;

use App\Http\Resources\CommentPostResource;
use App\Models\CommentPost;
use Egulias\EmailValidator\Parser\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddCommentEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $commentPost;
    public function __construct(CommentPost $commentPost)
    {
        $this->commentPost = $commentPost;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('add.comment.post'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'data' => new CommentPostResource($this->commentPost)
        ];
    }
}