<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LatestNews\{LatestNewsRequest, UpdateLatestNewsRequest};
use App\Http\Resources\Admin\LatestNews\{LatestNewsResource, LatestNewsShowResource};
use App\Models\LatestNews;
use Illuminate\Support\Facades\DB;
use Throwable;

class LatestNewsController extends Controller
{
    public function index()
    {
        $latest_news = LatestNews::latest()->paginate(100);
        return LatestNewsResource::collection($latest_news)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $latest_news = LatestNews::where('uuid', $uuid)->firstOrFail();
        return LatestNewsShowResource::make($latest_news)->additional(['status' => 200, 'message' => '']);
    }

    public function store(LatestNewsRequest $request)
    {
        DB::beginTransaction();
        try {
            $latest_news = LatestNews::create($request->validated() + ['admin_id' => auth('admin')->id()]);
            DB::commit();
            return LatestNewsResource::make($latest_news)->additional(['status' => 200, 'message' => 'latest news created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdateLatestNewsRequest $request, LatestNews $latest_news)
    {
        DB::beginTransaction();
        try {
            $latest_news->update($request->validated());
            DB::commit();
            return LatestNewsResource::make($latest_news)->additional(['status' => 200, 'message' => 'latest news updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy(LatestNews $latest_news)
    {
        $latest_news->delete();
        return response()->json(['message' => 'latest news deleted successfully']);
    }
}
