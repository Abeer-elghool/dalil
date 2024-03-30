<?php

namespace App\Http\Resources\User\ProtocolCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProtocolCategoryResource extends JsonResource
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
            'desc'              => $this->desc,
            'slug'              => $this->slug,
            'file'              => $this->file,
            'has_children'      => (bool) $this->has_children,
            'created_at'        => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null,
        ];
    }
}
