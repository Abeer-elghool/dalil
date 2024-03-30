<?php

namespace App\Http\Resources\User\LatestNews;

use App\Http\Resources\User\Comment\CommentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LatestNewsResource extends JsonResource
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
            'file'              => $this->file,
            'created_at'        => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null
        ];
    }
}
