<?php

namespace App\Http\Resources\Admin\Section;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Book\BookResource;
use App\Http\Resources\Admin\Comment\CommentResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'slug'=>$this->slug,
            'desc'=>$this->desc,
            'file'=>$this->file,
            'likes_count' => $this->likes()->where('type', 'like')->count(),
            'dislikes_count' => $this->likes()->where('type', 'dislike')->count(),
            'comments' => CommentResource::collection($this->comments),
            'book'=>new BookResource($this->book),
            'questions_count' => $this->questions->count(),
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=>$this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
