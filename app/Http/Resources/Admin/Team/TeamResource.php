<?php

namespace App\Http\Resources\Admin\Team;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'uuid'      => $this->uuid,
            'name'      => $this->name,
            'slug'      => $this->slug,
            'position'  => $this->position,
            'about'     => $this->about,
            'file'      => $this->file,
            'active'    => (bool) $this->active,
            'created_at'=> $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null
        ];
    }
}
