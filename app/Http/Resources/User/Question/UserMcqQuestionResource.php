<?php

namespace App\Http\Resources\User\Question;

use App\Http\Resources\User\Answer\AnswerResource;
use App\Models\UserMcqAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMcqQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'uuid'            => $this->uuid,
            'title'           => $this->title,
            'file'            => $this->file,
            'answered_before' => $this->answered_before,
            'answer_id'       => $this->answer_id,
            'answers'         => AnswerResource::collection($this->answers),
            'created_at'      => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null,
        ];
    }
}
