<?php

namespace App\Http\Resources\Admin\Helper;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Book\BookResource;

class SectionMiniResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'section' => [
            'id' => @$this->section->id,
            'title' => @$this->section->title,
            'slug' => @$this->section->slug,
            'questions_count' => @$this->questions_count,
            'desc'=>@$this->section->desc,
            'file'=>@$this->section->file,
            ],
            'book'=>new BookResource(@$this->section->book),
        ];
    }
}
