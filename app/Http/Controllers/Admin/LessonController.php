<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Book, Section, Chapter, Lesson};
use App\Http\Requests\Admin\Lesson\{LessonRequest, UpdateLessonRequest};
use App\Http\Resources\Admin\Lesson\LessonResource;
use App\Services\ElasticsearchService;
use Throwable;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lesson = Lesson::paginate(100);
        return (LessonResource::collection($lesson))->additional(['status' => 200, 'message' => 'request done']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LessonRequest $request)
    {
        //
        $chapter = Chapter::find($request->chapter_id);
        $lesson = Lesson::create($request->validated() + ['book_id' => $chapter->book_id, 'section_id' => $chapter->section_id]);
        try {
            $elasticsearch_service = new ElasticsearchService();
            $doc_saved = $elasticsearch_service->store_document('lessons', ['id' => $lesson->id, 'title' => $lesson->title, 'desc' => $lesson->desc, 'slug' => $lesson->slug]);
        } catch (Throwable $e) {
        }
        return (new LessonResource($lesson))->additional(['status' => 200, 'message' => 'request done']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $lesson = Lesson::where('slug', $slug)->firstOrFail();
        return (new LessonResource($lesson))->additional(['status' => 200, 'message' => 'request done']);
    }

    public function getById(string $id)
    {
        $lesson = Lesson::where('id', $id)->firstOrFail();
        return (new LessonResource($lesson))->additional(['status' => 200, 'message' => 'request done']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLessonRequest $request, string $id)
    {
        // $chapter = Chapter::find($request->chapter_id);
        $lesson  = Lesson::findOrFail($id);
        // $lesson->update($request->validated() + ['book_id' => $chapter->book_id, 'section_id' => $chapter->section_id]);
        $lesson->update($request->validated());
        return (new LessonResource($lesson))->additional(['status' => 200, 'message' => 'request done']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        if ($lesson->delete()) {
            return response()->json(['data' => null, 'message' => 'Lesson deleted successfully', 'status' => 200], 200);
        }
        return response()->json(['data' => null, 'message' => 'please contact support', 'status' => 200], 200);
    }
}
