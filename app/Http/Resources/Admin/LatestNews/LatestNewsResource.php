<?php

namespace App\Http\Resources\Admin\LatestNews;

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
            'desc'              => $this->desc,
            'author_name'       => $this->author_name,
            'slug'              => $this->slug,
            'file'              => $this->file,
            'active'            => (bool) $this->active,
            'created_at'        => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null
        ];
    }
}
