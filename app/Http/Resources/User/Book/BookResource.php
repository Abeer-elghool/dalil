<?php

namespace App\Http\Resources\User\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'desc' => $this->desc,
            'file' => $this->file,
            'views_count' => (int) $this->views_count,
            'downloads_count' => (int) $this->downloads()->count(),
            'sections_count' => (int) $this->sections()->count(),
            'chapters_count' => (int) $this->chapters()->count(),
            'lessons_count' => (int) $this->lessons()->count(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
