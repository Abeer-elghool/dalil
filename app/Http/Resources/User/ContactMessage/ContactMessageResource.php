<?php

namespace App\Http\Resources\User\ContactMessage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactMessageResource extends JsonResource
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
            'uuid'       => $this->uuid,
            'name'       => $this->name,
            'email'      => $this->email,
            'subject'    => $this->subject,
            'message'    => $this->message,
            'read_at'    => $this->read_at,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
