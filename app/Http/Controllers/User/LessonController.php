<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Http\Resources\User\Lesson\LessonResource;
use App\Services\ViewService;
use Illuminate\Support\Facades\Cache;

class LessonController extends Controller
{

    public function lessons_by_chapter_slug(Request $request, $chapter_slug)
    {
        $chapter = Chapter::where('slug', $chapter_slug)->firstOrFail();
        $lessons = Lesson::when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->where('chapter_id', $chapter->id)->get();
        return LessonResource::collection($lessons)->additional(['status' => 200, 'messages' => '']);
    }

    public function lessons_by_slug($slug)
    {
        if (Cache::has("lessons_by_slug_$slug")) {
            $lesson = Cache::get("lessons_by_slug_$slug");
        } else {
            $lesson = Lesson::where('slug', $slug)->firstOrFail();
            $lesson = LessonResource::make($lesson);
            Cache::forever("lessons_by_slug_$slug", $lesson);
        }
        $next_lesson = Lesson::where('chapter_id', $lesson->chapter_id)->where('id', '>', $lesson->id)->select('id', 'slug')->first();
        $last_lesson = Lesson::where('chapter_id', $lesson->chapter_id)->where('id', '<', $lesson->id)->select('id', 'slug')->first();
        ViewService::view($lesson->id, $lesson, 'App\\Models\\Lesson');
        return response()->json(['status' => 200, 'messages' => '', 'data' => $lesson, 'next_lesson' => $next_lesson, 'last_lesson' => $last_lesson]);
    }
}
