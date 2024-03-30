<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Article\{ArticleRequest, UpdateArticleRequest};
use App\Http\Resources\Admin\Article\{ArticleResource, ArticleShowResource};
use App\Models\Article;
use App\Services\ElasticsearchService;
use Illuminate\Support\Facades\DB;
use Throwable;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->paginate(100);
        return ArticleResource::collection($articles)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $article = Article::where('uuid', $uuid)->firstOrFail();
        return ArticleShowResource::make($article)->additional(['status' => 200, 'message' => '']);
    }

    public function store(ArticleRequest $request)
    {
        DB::beginTransaction();
        try {
            $article = Article::create($request->validated() + ['admin_id' => auth('admin')->id()]);
            try {
                $elasticsearch_service = new ElasticsearchService();
                $doc_saved = $elasticsearch_service->store_document('articles', ['id' => $article->id, 'title' => $article->title, 'body' => $article->body, 'slug' => $article->slug]);
            } catch (Throwable $e) {
            }
            DB::commit();
            return ArticleResource::make($article)->additional(['status' => 200, 'message' => 'article created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        DB::beginTransaction();
        try {
            $article->update($request->validated());
            DB::commit();
            return ArticleResource::make($article)->additional(['status' => 200, 'message' => 'article updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json(['message' => 'Article deleted successfully']);
    }
}
