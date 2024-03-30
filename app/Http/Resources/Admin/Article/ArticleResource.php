<?php

namespace App\Http\Resources\Admin\Article;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'body'              => $this->body,
            'file'              => $this->file,
            'link'              => $this->link,
            'active'            => (bool) $this->active,
            'created_at'        => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null
        ];
    }
}
