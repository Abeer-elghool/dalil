<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Photo\{PhotoRequest, UpdatePhotoRequest};
use App\Http\Resources\Admin\Photo\{PhotoResource, PhotoShowResource};
use App\Models\Photo;
use Illuminate\Support\Facades\DB;
use Throwable;

class PhotoController extends Controller
{
    public function index()
    {
        $photos = Photo::latest()->paginate(100);
        return PhotoResource::collection($photos)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $photo = Photo::where('uuid', $uuid)->firstOrFail();
        return PhotoShowResource::make($photo)->additional(['status' => 200, 'message' => '']);
    }

    public function store(PhotoRequest $request)
    {
        DB::beginTransaction();
        try {
            $photo = Photo::create($request->validated() + ['admin_id' => auth('admin')->id()]);
            DB::commit();
            return PhotoResource::make($photo)->additional(['status' => 200, 'message' => 'photo created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdatePhotoRequest $request, Photo $photo)
    {
        DB::beginTransaction();
        try {
            $photo->update($request->validated());
            DB::commit();
            return PhotoResource::make($photo)->additional(['status' => 200, 'message' => 'photo updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy(Photo $photo)
    {
        $photo->delete();
        return response()->json(['message' => 'photo deleted successfully']);
    }
}
