<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Mcq\{McqRequest, UpdateMcqRequest};
use App\Http\Resources\Admin\Mcq\{McqResource, McqShowResource};
use App\Models\Mcq;
use App\Models\Question;
use App\Services\ElasticsearchService;
use Illuminate\Support\Facades\DB;
use Throwable;

class McqController extends Controller
{
    public function index()
    {
        $mcqs = Mcq::latest()->paginate(100);
        return McqResource::collection($mcqs)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $mcq = Mcq::where('uuid', $uuid)->firstOrFail();
        return McqShowResource::make($mcq)->additional(['status' => 200, 'message' => '']);
    }

    public function store(McqRequest $request)
    {
        DB::beginTransaction();
        try {
            $mcq = $this->createMcq($request);
            if ($mcq->questions_type == 'random') {
                $this->attachRandomQuestions($mcq, $mcq->questions_count);
            } elseif ($mcq->questions_type == 'specified') {
                if ($mcq->mcq_sections === 'section') {
                    $this->attachSectionQuestions($mcq, $request->sections);
                } elseif ($mcq->mcq_sections === 'chapter') {
                    $this->attachChapterQuestions($mcq, $request->chapters);
                }
            }
            try {
                $elasticsearch_service = new ElasticsearchService();
                $doc_saved = $elasticsearch_service->store_document('mcqs', ['id' => $mcq->id, 'title' => $mcq->title, 'desc' => $mcq->desc, 'slug' => $mcq->slug]);
            } catch (Throwable $e) {
            }
            DB::commit();
            return McqResource::make($mcq)->additional(['status' => 200, 'message' => 'MCQ created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    private function createMcq($request)
    {
        $score = $request->question_score * $request->questions_count;
        return Mcq::create($request->safe()->only(['title', 'questions_count', 'duration', 'questions_type', 'question_score', 'mcq_sections', 'file']) + ['score' => $score]);
    }

    private function attachRandomQuestions($mcq, $questionsCount)
    {
        $questions = Question::inRandomOrder()->limit($questionsCount)->pluck('id')->toArray();
        $mcq->questions()->attach($questions);
    }

    private function attachSectionQuestions($mcq, $sections)
    {
        $questionIds = [];
        $sectionData = [];
        foreach ($sections as $section) {
            $questions = Question::inRandomOrder()
                ->where('section_id', $section['id'])
                ->limit($section['questions_count'])
                ->distinct()
                ->pluck('id')
                ->toArray();
            $sectionData[$section['id']] = ['questions_count' => $section['questions_count']];
            $questionIds = array_merge($questionIds, $questions);
        }
        $mcq->sections()->attach($sectionData);
        $mcq->questions()->attach($questionIds);
    }

    private function attachChapterQuestions($mcq, $chapters)
    {
        $questionIds = [];
        $chapterData = [];
        foreach ($chapters as $chapter) {
            $questions = Question::inRandomOrder()
                ->where('chapter_id', $chapter['id'])
                ->limit($chapter['questions_count'])
                ->distinct()
                ->pluck('id')
                ->toArray();
            $chapterData[$chapter['id']] = ['questions_count' => $chapter['questions_count']];
            $questionIds = array_merge($questionIds, $questions);
        }
        $mcq->chapters()->attach($chapterData);
        $mcq->questions()->attach($questionIds);
    }

    public function update(UpdateMcqRequest $request, Mcq $mcq)
    {
        DB::beginTransaction();
        try {
            $mcq->update($request->safe()->only(['title', 'duration', 'file']));
            DB::commit();
            return McqResource::make($mcq)->additional(['status' => 200, 'message' => 'MCQ updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'Update failed.'], 500);
        }
    }

    public function destroy(Mcq $mcq)
    {
        $mcq->sections()->detach();
        $mcq->chapters()->detach();
        $mcq->delete();

        return response()->json(['message' => 'MCQ deleted successfully']);
    }
}
