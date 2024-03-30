<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\Lecture\LectureResource;
use App\Models\Lecture;
use App\Services\ViewService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LectureController extends Controller
{
    function lectures(): AnonymousResourceCollection
    {
        return LectureResource::collection(Lecture::active()->latest()->get())->additional(['status' => 200, 'message' => '']);
    }

    function lecture_by_slug($slug): LectureResource
    {
        $lecture = Lecture::active()->where('slug', $slug)->firstOrFail();
        ViewService::view($lecture->id, $lecture, 'App\\Models\\Lecture');
        return LectureResource::make($lecture)->additional(['status' => 200, 'message' => '']);
    }

    function video_views($slug): LectureResource
    {
        $lecture = Lecture::active()->where('slug', $slug)->firstOrFail();
        $lecture->increment('video_views_count', 1);
        return LectureResource::make($lecture)->additional(['status' => 200, 'message' => '']);
    }
}
