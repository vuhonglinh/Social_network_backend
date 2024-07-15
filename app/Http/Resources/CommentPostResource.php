<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'post' => new PostResource($this->post),
            'user' => new UserResource($this->user), // Ensure 'user' is loaded correctly
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'parent_id' => $this->parent_id,
            'reply' => $this->when($this->reply->isNotEmpty(), CommentPostResource::collection($this->reply)),
        ];
    }
}
