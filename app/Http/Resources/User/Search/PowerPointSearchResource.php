<?php

namespace App\Http\Resources\User\Search;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PowerPointSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'type'=>'power_point',
            'title'=>$this->title,
            'slug'=>$this->slug,
        ];
    }
}
