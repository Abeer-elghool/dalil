<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Http\Resources\User\Article\ArticleResource;
use App\Http\Resources\User\ArticleCategory\ArticleCategoryResource;
use App\Models\ArticleCategory;
use App\Services\ViewService;

class ArticleController extends Controller
{

    public function article_categories(Request $request)
    {
        $article_categories = ArticleCategory::active()->when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->latest()->get();
        return ArticleCategoryResource::collection($article_categories)->additional(['status' => 200, 'messages' => '']);
    }

    public function articles_by_category_slug($slug)
    {
        $article_category = ArticleCategory::active()->where('slug', $slug)->latest()->firstOrFail();
        $articles = Article::active()->where('article_category_id', $article_category->id)->select('id', 'slug', 'title')->latest()->get();
        return response()->json(['status' => 200, 'data' => $articles, 'messages' => '']);
    }

    public function articles(Request $request)
    {
        $articles = Article::active()->when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->latest()->get();
        return ArticleResource::collection($articles)->additional(['status' => 200, 'messages' => '']);
    }

    public function article_by_slug($slug)
    {
        $article = Article::active()->where('slug', $slug)->latest()->firstOrFail();
        ViewService::view($article->id, $article, 'App\\Models\\Article');
        return ArticleResource::make($article)->additional(['status' => 200, 'messages' => '']);
    }
}
