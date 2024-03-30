<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LatestNews;
use App\Http\Resources\User\LatestNews\LatestNewsResource;
use App\Services\ViewService;

class LatestNewsController extends Controller
{
    public function latest_news(Request $request)
    {
        $latest_news = LatestNews::when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->latest()->get();
        return LatestNewsResource::collection($latest_news)->additional(['status' => 200, 'messages' => '']);
    }

    public function latest_news_slug($slug)
    {
        $latest_news = LatestNews::where('slug', $slug)->firstOrFail();
        ViewService::view($latest_news->id, $latest_news, 'App\\Models\\LatestNews');
        return LatestNewsResource::make($latest_news)->additional(['status' => 200, 'messages' => '']);
    }
}
