<?php

namespace App\Http\Resources\User\Protocol;

use App\Http\Resources\User\Book\BookResource;
use App\Http\Resources\User\Chapter\ChapterResource;
use App\Http\Resources\User\Comment\CommentResource;
use App\Http\Resources\User\Lesson\LessonResource;
use App\Http\Resources\User\Section\SectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProtocolResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'slug'          => $this->slug,
            'desc'          => $this->desc,
            'file'          => $this->file,
            'book'          => new BookResource($this->book),
            'section'       => new SectionResource($this->section),
            'chapter'       => new ChapterResource($this->chapter),
            'lesson'        => new LessonResource($this->lesson),
            'downloads_count' => (int) $this->downloads()->count(),
            'views_count'   => (int)$this->views_count,
            'likes_count'   => $this->likes()->where('type', 'like')->count(),
            'dislikes_count'=> $this->likes()->where('type', 'dislike')->count(),
            'user_liked'    => auth('api')->check() ? $this->likes()->where(['user_id' => auth('api')->id(), 'type' => 'like'])->exists() : false,
            'user_disliked' => auth('api')->check() ? $this->likes()->where(['user_id' => auth('api')->id(), 'type' => 'dislike'])->exists() : false,
            'comments'      => CommentResource::collection($this->comments),
            'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'    => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
