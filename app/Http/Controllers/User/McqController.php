<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Mcq\AnswerQuestionRequest;
use App\Http\Resources\User\Answer\AnswerResource;
use App\Http\Resources\User\Mcq\{McqResource, McqShowResource, UserMcqResource, UserMcqShowResource};
use App\Models\Answer;
use App\Models\Mcq;
use App\Models\Question;
use App\Models\UserMcq;
use App\Models\UserMcqAnswer;
use App\Services\ViewService;

class McqController extends Controller
{
    public function mcqs()
    {
        $mcqs = Mcq::latest()->paginate(100);
        return McqResource::collection($mcqs)->additional(['status' => 200, 'message' => '']);
    }

    public function mcq_by_slug($slug)
    {
        $mcq = Mcq::where('slug', $slug)->firstOrFail();
        ViewService::view($mcq->id, $mcq, 'App\\Models\\Mcq');
        return McqShowResource::make($mcq)->additional(['status' => 200, 'message' => '']);
    }

    public function start_mcq($slug)
    {
        $mcq = Mcq::where('slug', $slug)->firstOrFail();
        $user_mcq = UserMcq::where(['user_id' => auth('api')->id(), 'mcq_id' => $mcq->id, 'status' => 'in_progress'])->first();
        if (!$user_mcq) {
            $user_mcq = UserMcq::create(['user_id' => auth('api')->id(), 'mcq_id' => $mcq->id, 'status' => 'in_progress', 'questions_count' => $mcq->questions->count()]);
        }
        return UserMcqResource::make($user_mcq)->additional(['status' => 200, 'message' => '']);
    }

    public function answer_question(AnswerQuestionRequest $request)
    {
        $mcq = Mcq::where('uuid', $request->mcq_id)->firstOrFail();
        $question = Question::where('uuid', $request->question_id)->firstOrFail();
        $answer = Answer::where(['uuid' => $request->answer_id, 'question_id' => $question->id])->firstOrFail();

        $user = auth('api')->user();
        $user_mcq = $user->user_mcqs()->where(['mcq_id' => $mcq->id, 'status' => 'in_progress'])->firstOrFail();

        $user_mcq_answer = $user_mcq->user_mcq_answers()->where('question_id', $question->id)->first();

        $is_correct = $answer->is_correct;
        $question_score = $is_correct ? $mcq->question_score : 0;

        if (!$user_mcq_answer) {
            $user_mcq_answer = $user_mcq->user_mcq_answers()->create([
                'user_id' => $user->id,
                'mcq_id' => $mcq->id,
                'question_id' => $question->id,
                'answer_id' => $answer->id,
                'is_correct' => $is_correct,
                'question_score' => $question_score,
            ]);

            $user_mcq->increment('answered_questions_count');

            if ($user_mcq->answered_questions_count == $user_mcq->questions_count) {
                $user_mcq->update(['status' => 'finished']);
            }
        } else {
            $user_mcq_answer->update([
                'answer_id' => $answer->id,
                'is_correct' => $is_correct,
                'question_score' => $question_score,
            ]);
        }

        return response()->json(['status' => 200, 'data' => null, 'message' => 'Answer Saved Successfully']);
    }

    public function my_mcqs()
    {
        $mcqs = UserMcq::where(['user_id' => auth('api')->id()])->latest()->paginate(100);
        return UserMcqShowResource::collection($mcqs)->additional(['status' => 200, 'message' => '']);
    }

    public function correct_answer(Question $question)
    {
        $answer = $question->answers->where('is_correct', 1)->firstOrFail();
        return AnswerResource::make($answer)->additional(['status' => 200, 'message' => '']);
    }
}
