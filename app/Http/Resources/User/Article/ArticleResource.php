<?php

namespace App\Http\Resources\User\Article;

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
            'views_count'       => (int)$this->views_count,
            'likes_count'       => $this->likes()->where('type', 'like')->count(),
            'dislikes_count'    => $this->likes()->where('type', 'dislike')->count(),
            'user_liked'        => auth('api')->check() ? $this->likes()->where(['user_id' => auth('api')->id(), 'type' => 'like'])->exists() : false,
            'user_disliked'     => auth('api')->check() ? $this->likes()->where(['user_id' => auth('api')->id(), 'type' => 'dislike'])->exists() : false,
            'active'            => (bool) $this->active,
            'created_at'        => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null
        ];
    }
}