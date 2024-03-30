<?php

namespace App\Http\Resources\Admin\Article;

use App\Http\Resources\Admin\ArticleCategory\ArticleCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleShowResource extends JsonResource
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
            'body'              => $this->body,
            'file'              => $this->file,
            'link'              => $this->link,
            'active'            => (bool) $this->active,
            'category'          => ArticleCategoryResource::make($this->category),
            'created_at'        => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null,
        ];
    }
}
