<?php

namespace App\Http\Resources\User\Mcq;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMcqShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $correct_answers_count = (int) $this->user_mcq_answers->where('is_correct', 1)->count();
        return [
            'id'                => $this->id,
            'mcq_id'            => $this->mcq->id,
            'title'             => $this->mcq->title,
            'slug'              => $this->mcq->slug,
            'questions_count'   => (int) $this->questions_count,
            'file'              => $this->mcq->file,
            'user_score'        => (int) $this->user_mcq_answers->sum('question_score'),
            'correct_questions_count' => $correct_answers_count,
            'correct_questions_percent' => (int) ($correct_answers_count / $this->questions_count * 100),
            'created_at'        => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null,
            'finished_at'       => $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null,
        ];
    }
}
