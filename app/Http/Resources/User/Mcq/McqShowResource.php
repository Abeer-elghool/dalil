<?php

namespace App\Http\Resources\User\Mcq;

use App\Http\Resources\User\Question\QuestionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class McqShowResource extends JsonResource
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
            'questions_count'   => $this->questions_count,
            'duration'          => $this->duration,
            'questions_type'    => $this->questions_type,
            'file'              => $this->file,
            'questions'         => QuestionResource::collection($this->questions),
            'views_count'       => (int)$this->views_count,
            'likes_count'       => $this->likes()->where('type', 'like')->count(),
            'dislikes_count'    => $this->likes()->where('type', 'dislike')->count(),
            'user_liked'        => auth('api')->check() ? $this->likes()->where(['user_id' => auth('api')->id(), 'type' => 'like'])->exists() : false,
            'user_disliked'     => auth('api')->check() ? $this->likes()->where(['user_id' => auth('api')->id(), 'type' => 'dislike'])->exists() : false,
            'created_at'        => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null,
        ];
    }
}
