<?php

namespace App\Http\Resources\User\ArticleCategory;

use App\Http\Resources\Admin\Article\ArticleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleCategoryShowResource extends JsonResource
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
            'file'              => $this->file,
            'active'            => (bool) $this->active,
            'articles'          => ArticleResource::collection($this->articles),
            'articles_count'    => $this->articles->count(),
            'created_at'        => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null,
        ];
    }
}
