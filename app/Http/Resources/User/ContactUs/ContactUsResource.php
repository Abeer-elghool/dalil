<?php

namespace App\Http\Resources\User\ContactUs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactUsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'key'        => $this->key,
            'value'      => $this->value,
            'active'     => (bool) $this->active,
            'file'       => $this->file,
            'created_at' => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null
        ];
    }
}
