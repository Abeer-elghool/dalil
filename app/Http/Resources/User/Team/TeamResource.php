<?php

namespace App\Http\Resources\User\Team;

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
            'file'      => $this->file
        ];
    }
}
