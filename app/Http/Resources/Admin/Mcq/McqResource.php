<?php

namespace App\Http\Resources\Admin\Mcq;

use App\Http\Resources\Admin\Helper\SectionMiniResource;
use App\Http\Resources\Admin\Helper\ChapterMiniResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class McqResource extends JsonResource
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
            'question_score'    => $this->question_score,
            'score'             => $this->score,
            'duration'          => $this->duration,
            'questions_type'    => $this->questions_type,
            'mcq_sections'      => $this->mcq_sections,
            // 'sections'          => SectionMiniResource::collection($this->sections),
            // 'chapters'          => ChapterMiniResource::collection($this->chapters),
            'data'              => $this->mcq_sections == 'section' ? SectionMiniResource::collection($this->mcqSections): ChapterMiniResource::collection($this->mcqSections), 
            'file'              => $this->file,
            'created_at'        => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null,
        ];
    }
}
