<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Book, Section, Chapter};
use App\Http\Requests\Admin\Chapter\{ChapterRequest, UpdateChapterRequest};
use App\Http\Resources\Admin\Chapter\ChapterResource;
use App\Services\ElasticsearchService;
use Throwable;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chapter = Chapter::paginate(100);
        return (ChapterResource::collection($chapter))->additional(['status' => 200, 'message' => 'request done']);
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
    public function store(ChapterRequest $request)
    {
        //
        $section = Section::find($request->section_id);
        $chapter = Chapter::create($request->validated() + ['book_id' => $section->book_id]);
        try {
            $elasticsearch_service = new ElasticsearchService();
            $doc_saved = $elasticsearch_service->store_document('chapters', ['id' => $chapter->id, 'title' => $chapter->title, 'desc' => $chapter->desc, 'slug' => $chapter->slug]);
        } catch (Throwable $e) {
        }
        return (new ChapterResource($chapter))->additional(['status' => 200, 'message' => 'request done']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $chapter = Chapter::where('slug', $slug)->firstOrFail();
        return (new ChapterResource($chapter))->additional(['status' => 200, 'message' => 'request done']);
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
    public function update(UpdateChapterRequest $request, string $id)
    {
        $section = Section::find($request->section_id);
        $chapter  = Chapter::findOrFail($id);
        $chapter->update($request->validated() + ['book_id' => $section->book_id]);
        return (new ChapterResource($chapter))->additional(['status' => 200, 'message' => 'request done']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chapter $chapter)
    {
        if ($chapter->delete()) {
            return response()->json(['data' => null, 'message' => 'Chapter deleted successfully', 'status' => 200], 200);
        }
        return response()->json(['data' => null, 'message' => 'please contact support', 'status' => 200], 200);
    }
}
