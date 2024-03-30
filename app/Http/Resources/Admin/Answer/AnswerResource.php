<?php

namespace App\Http\Resources\Admin\Answer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
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
            'answer'     => $this->answer,
            'is_correct' => $this->is_correct,
            'question_id'=> $this->question_id,
            'created_at' => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null,
        ];
    }
}
