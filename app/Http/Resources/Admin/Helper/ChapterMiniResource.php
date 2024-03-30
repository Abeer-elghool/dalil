<?php

namespace App\Http\Resources\Admin\Helper;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Book\BookResource;
use App\Http\Resources\Admin\Section\SectionResource;

class ChapterMiniResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'chapter' => [
            'id' => @$this->chapter->id,
            'title' => @$this->chapter->title,
            'slug' => @$this->chapter->slug,
            'questions_count' => @$this->questions_count,
            'desc'=>@$this->chapter->desc,
            'file'=>@$this->chapter->file,
            ],
            'book'=>new BookResource(@$this->chapter->book),
            'section'=>new SectionResource(@$this->chapter->section),
        ];
    }
}
