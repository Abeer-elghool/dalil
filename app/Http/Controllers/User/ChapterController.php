<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Http\Resources\User\Chapter\ChapterResource;
use App\Services\ViewService;

class ChapterController extends Controller
{

    public function chapters_by_section_slug(Request $request, $section_slug)
    {
        $section = Section::where('slug', $section_slug)->firstOrFail();
        $chapters = Chapter::when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->where('section_id', $section->id)->get();
        return ChapterResource::collection($chapters)->additional(['status' => 200, 'messages' => '']);
    }

    public function chapters_by_slug($slug)
    {
        $chapter = Chapter::where('slug', $slug)->firstOrFail();
        ViewService::view($chapter->id, $chapter, 'App\\Models\\Chapter');
        return ChapterResource::make($chapter)->additional(['status' => 200, 'messages' => '']);
    }
}
