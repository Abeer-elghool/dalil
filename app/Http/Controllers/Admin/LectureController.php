<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Lecture\{LectureRequest, UpdateLectureRequest};
use App\Http\Resources\Admin\Lecture\{LectureResource, LectureShowResource};
use App\Models\Lecture;
use App\Services\ElasticsearchService;
use Illuminate\Support\Facades\DB;
use Throwable;
use Vimeo\Laravel\Facades\Vimeo;

class LectureController extends Controller
{
    public function index()
    {
        $lectures = Lecture::latest()->paginate(100);
        return LectureResource::collection($lectures)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $lecture = Lecture::where('uuid', $uuid)->firstOrFail();
        return LectureShowResource::make($lecture)->additional(['status' => 200, 'message' => '']);
    }

    public function store(LectureRequest $request)
    {
        try {
            $uploadedVideo = Vimeo::upload($request->file);
        } catch (Throwable $e) {
            return response()->json(['status' => 422, 'data' => null, 'message' => 'You’ve hit your maximum number of uploads for today.'], 422);
        }
        DB::beginTransaction();
        try {
            $lecture = Lecture::create($request->validated() + ['admin_id' => auth('admin')->id(), 'file_name' => $request->file('file')->getClientOriginalName()]);

            $videoUrl = $this->extractVideoIdFromUrl($uploadedVideo);
            $lecture->update(['file' => $videoUrl]);
            try {
                $elasticsearch_service = new ElasticsearchService();
                $doc_saved = $elasticsearch_service->store_document('lectures', ['id' => $lecture->id, 'title' => $lecture->title, 'desc' => $lecture->desc, 'slug' => $lecture->slug]);
            } catch (Throwable $e) {
            }
            DB::commit();
            return LectureResource::make($lecture)->additional(['status' => 200, 'message' => 'Lecture created successfully.']);
        } catch (Throwable $e) {
            dd($e);
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdateLectureRequest $request, Lecture $lecture)
    {
        try {
            if ($request->file('file')) {
                $uploadedVideo = Vimeo::upload($request->file);
            }
        } catch (Throwable $e) {
            return response()->json(['status' => 422, 'data' => null, 'message' => 'You’ve hit your maximum number of uploads for today.'], 422);
        }
        DB::beginTransaction();
        try {
            $lecture->update($request->validated());
            if ($request->file('file') && $uploadedVideo) {
                Vimeo::request("/videos/$lecture->file", [], 'DELETE');
                $videoUrl = $this->extractVideoIdFromUrl($uploadedVideo);
                $lecture->update(['file' => $videoUrl, 'file_name' => $request->file('file')->getClientOriginalName()]);
            }
            DB::commit();
            return LectureResource::make($lecture)->additional(['status' => 200, 'message' => 'Lecture updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy(Lecture $lecture)
    {
        DB::beginTransaction();
        try {
            Vimeo::request("/videos/$lecture->file", [], 'DELETE');
            $lecture->delete();
            DB::commit();
            return response()->json(['message' => 'Lecture deleted successfully']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    private function extractVideoIdFromUrl($videoUrl): string
    {
        $parts = explode('/', $videoUrl);
        return end($parts);
    }
}
