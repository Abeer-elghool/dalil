<?php

namespace App\Http\Resources\Admin\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'data'          => $this->data,
            'notifiable_id' => $this->notifiable_id,
            'read_at'       => $this->read_at ? $this->read_at->format('Y-m-d H:i A') : null,
            'created_at'    => $this->created_at ? $this->created_at->format('Y-m-d H:i A') : null,
        ];
    }
}
