<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'user' => new UserResource($this->user),
            'description' => $this->description,
            'post_tag' => UserResource::collection($this->userTags),
            'images' => $this->when($this->images->isNotEmpty(), PostImageResource::collection($this->whenLoaded('images'))),
            'likes' => $this->likes ? $this->likes->count() : 0,
            'users' => $this->likes ? $this->likes()->with('user')->get() : [],
            'created_at' => $this->created_at
        ];
    }
}
