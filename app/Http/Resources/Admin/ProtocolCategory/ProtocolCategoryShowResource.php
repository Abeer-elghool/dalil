<?php

namespace App\Http\Resources\Admin\ProtocolCategory;

use App\Http\Resources\Admin\Protocol\ProtocolResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProtocolCategoryShowResource extends JsonResource
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
            'file'              => $this->file,
            'has_children'      => (bool) $this->has_children,
            'protocols'         => ProtocolResource::collection($this->protocols),
            'protocols_count'   => $this->protocols->count(),
            'created_at'        => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null,
        ];
    }
}
