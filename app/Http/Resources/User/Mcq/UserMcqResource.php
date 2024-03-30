<?php

namespace App\Http\Resources\User\Mcq;

use App\Http\Resources\User\Question\UserMcqQuestionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\UserMcqAnswer;

class UserMcqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->mcq->questions->map(function ($question) {
            $user_mcq_answer = UserMcqAnswer::where(['question_id' => $question->id, 'mcq_id' => $this->mcq->id, 'user_id' => auth('api')->id(), 'user_mcq_id' => $this->id])->first();
            $question['answered_before'] = false;
            $question['answer_id'] = null;
            if ($user_mcq_answer) {
                $question['answered_before'] = true;
                $question['answer_id'] = $user_mcq_answer->answer_id;
            }
            return $question;
        });
        return [
            'id'                => $this->mcq->id,
            'uuid'              => $this->mcq->uuid,
            'title'             => $this->mcq->title,
            'slug'              => $this->mcq->slug,
            'questions_count'   => $this->mcq->questions_count,
            'duration'          => $this->mcq->duration,
            'questions_type'    => $this->mcq->questions_type,
            'file'              => $this->mcq->file,
            'questions'         => UserMcqQuestionResource::collection($data),
            'created_at'        => $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null,
        ];
    }
}
