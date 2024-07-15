<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageResourceCollection extends ResourceCollection
{
    /**
     * Chuyển đổi bộ sưu tập tài nguyên thành một mảng.
     *
     * @param  Request  $request
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($message) {
                return new MessageResource($message);
            }),
        ];
    }
}