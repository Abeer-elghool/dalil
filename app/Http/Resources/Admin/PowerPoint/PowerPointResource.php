<?php

namespace App\Http\Resources\Admin\PowerPoint;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PowerPointResource extends JsonResource
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
            'desc'              => $this->desc,
            'file'              => $this->file,
            'pdf_file'          => $this->pdf_file,
            'link'              => $this->link,
            'active'            => (bool) $this->active,
            'created_at'        => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null
        ];
    }
}
