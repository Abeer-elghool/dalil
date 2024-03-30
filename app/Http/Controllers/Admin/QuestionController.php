<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Question\{QuestionRequest, UpdateQuestionRequest};
use App\Http\Resources\Admin\Question\QuestionResource;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Throwable;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::latest()->paginate(100);
        return QuestionResource::collection($questions)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $question = Question::where('uuid', $uuid)->firstOrFail();
        return QuestionResource::make($question)->additional(['status' => 200, 'message' => '']);
    }

    public function store(QuestionRequest $request)
    {
        $section_id = null;
        $chapter_id = null;
        $lesson_id = null;
        switch ($request->related_to) {
            case ('lesson'):
                $lesson = Lesson::findOrFail($request->lesson_id);
                $section_id = $lesson->section_id;
                $chapter_id = $lesson->chapter_id;
                $lesson_id = $request->lesson_id;
                break;
            case ('chapter'):
                $chapter = Chapter::findOrFail($request->chapter_id);
                $chapter_id = $request->chapter_id;
                $section_id = $chapter->section_id;
                break;
            case ('section'):
                $section_id = $request->section_id;
                break;
        }
        DB::beginTransaction();
        try {
            $question = Question::create($request->safe()->only(['title', 'file']) + ['section_id' => $section_id, 'chapter_id' => $chapter_id, 'lesson_id' => $lesson_id]);
            foreach ($request->answers as $index => $answer) {
                $isCorrect = ($index == $request->correct_answer_index);
                $question->answers()->create([
                    'answer'     => $answer,
                    'is_correct' => $isCorrect,
                ]);
            }
            DB::commit();
            return QuestionResource::make($question)->additional(['status' => 200, 'message' => 'question created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        DB::beginTransaction();
        try {
            $question->update($request->safe()->only(['title', 'file']));
            if ($request->answers && is_array($request->answers)) {
                foreach ($request->answers as $index => $answer) {
                    $question->answers()->whereId($answer['id'])->update([
                        'answer' => $answer['answer']
                    ]);
                }
            }
            DB::commit();
            return QuestionResource::make($question)->additional(['status' => 200, 'message' => 'question updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();
        return response()->json(['status' => 200, 'data' => null, 'message' => 'question deleted successfully.'], 200);
    }
}
