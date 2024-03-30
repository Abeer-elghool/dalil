<?php

namespace App\Http\Resources\Admin\Comment;

use App\Http\Resources\User\User\SimpleUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'body'       => $this->body,
            'user'       => SimpleUserResource::make($this->user),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
