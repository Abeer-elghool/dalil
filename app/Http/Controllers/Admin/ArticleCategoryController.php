<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleCategory\{ArticleCategoryRequest, UpdateArticleCategoryRequest};
use App\Http\Resources\Admin\ArticleCategory\{ArticleCategoryResource, ArticleCategoryShowResource};
use App\Models\ArticleCategory;
use Illuminate\Support\Facades\DB;
use Throwable;

class ArticleCategoryController extends Controller
{
    public function index()
    {
        $categories = ArticleCategory::latest()->paginate(100);
        return ArticleCategoryResource::collection($categories)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $category = ArticleCategory::where('uuid', $uuid)->firstOrFail();
        return ArticleCategoryShowResource::make($category)->additional(['status' => 200, 'message' => '']);
    }

    public function store(ArticleCategoryRequest $request)
    {
        DB::beginTransaction();
        try {
            $category = ArticleCategory::create($request->validated() + ['admin_id' => auth('admin')->id()]);
            DB::commit();
            return ArticleCategoryResource::make($category)->additional(['status' => 200, 'message' => 'category created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            dd($e);
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdateArticleCategoryRequest $request, ArticleCategory $article_category)
    {
        DB::beginTransaction();
        try {
            $article_category->update($request->validated());
            DB::commit();
            return ArticleCategoryResource::make($article_category)->additional(['status' => 200, 'message' => 'category updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy(ArticleCategory $article_category)
    {
        $article_category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
