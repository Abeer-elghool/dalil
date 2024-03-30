<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function home() : JsonResponse {
        $data = [];
        $data['sections_count'] = Section::count();
        $data['chapters_count'] = Chapter::count();
        $data['lessons_count'] = Lesson::count();
        $data['views_count'] =100;
        $data['downloads_count'] =100;
        return response()->json(['status' => 200, 'data' => $data, 'messages' => '']);
    }
}
