<?php

namespace App\Http\Resources\User\Search;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonSearchResource extends JsonResource
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
            'type'=>'lesson',
            'title'=>$this->title,
            'slug'=>$this->slug,
            'book_slug'=>optional($this->book)->slug,
            'section_slug'=>optional($this->section)->slug,
            'chapter_slug'=>optional($this->chapter)->slug,
        ];
    }
}
