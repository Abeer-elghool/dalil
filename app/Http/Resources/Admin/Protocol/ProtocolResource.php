<?php

namespace App\Http\Resources\Admin\Protocol;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Book\BookResource;
use App\Http\Resources\Admin\Section\SectionResource;
use App\Http\Resources\Admin\Chapter\ChapterResource;
use App\Http\Resources\Admin\Lesson\LessonResource;
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
            'id'=>$this->id,
            'title'=>$this->title,
            'slug'=>$this->slug,
            'desc'=>$this->desc,
            'file'=>$this->file,
            'book'=>new BookResource($this->book),
            'section'=>new SectionResource($this->section),
            'chapter'=>new ChapterResource($this->chapter),
            'lesson'=>new LessonResource($this->lesson),
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=>$this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
