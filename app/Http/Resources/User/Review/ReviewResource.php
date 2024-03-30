<?php

namespace App\Http\Resources\User\Review;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'uuid'              => $this->uuid,
            'title'             => $this->title,
            'slug'              => $this->slug,
            'desc'              => $this->desc,
            'author_name'       => $this->author_name,
            'author_position'   => $this->author_position,
            'file'              => $this->file,
            'created_at'        => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null
        ];
    }
}
