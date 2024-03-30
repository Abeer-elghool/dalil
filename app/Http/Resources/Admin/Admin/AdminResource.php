<?php

namespace App\Http\Resources\Admin\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'uuid'          => $this->uuid,
            'name'          => $this->name,
            'image'         => $this->image,
            'phone'         => (int)$this->phone,
            'email'         => $this->email,
            'user_type'     => $this->user_type,
            'gender'        => $this->gender,
            'active'        => (bool)$this->active,
            'created_at'    => $this->created_at ? $this->created_at->format('Y-m-d') : null,
        ];
    }
}
