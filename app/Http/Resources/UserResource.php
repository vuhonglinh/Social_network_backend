<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'address' => $this->address,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'cover_image' => $this->cover_image,
        ];
    }
}