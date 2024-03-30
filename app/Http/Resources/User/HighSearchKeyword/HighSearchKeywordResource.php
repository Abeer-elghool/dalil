<?php

namespace App\Http\Resources\User\HighSearchKeyword;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HighSearchKeywordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'keyword' => $this->keyword,
        ];
    }
}
