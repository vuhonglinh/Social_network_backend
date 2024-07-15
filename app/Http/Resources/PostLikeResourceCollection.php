<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostLikeResourceCollection extends ResourceCollection
{
    protected $post;

    public function __construct($resource, $post)
    {
        parent::__construct($resource);
        $this->post = $post;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'likes' => $this->collection->count(),
            'post' => new PostResource($this->post), // Assuming you have a PostResource
            'users' => $this->collection, // Assuming `user` relationship is loaded
        ];
    }
}