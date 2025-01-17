<?php

namespace App\Http\Resources\Admin\Question;

use App\Http\Resources\Admin\Answer\AnswerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'title'     => $this->title,
            'file'      => $this->file,
            'answers'   => AnswerResource::collection($this->answers),
            'created_at'=> $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null,
        ];
    }
}
