<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StaticPage\{StaticPageRequest, UpdateStaticPageRequest};
use App\Http\Resources\Admin\StaticPage\{StaticPageResource, StaticPageShowResource};
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class StaticPageController extends Controller
{
    public function index()
    {
        $static_page = StaticPage::latest()->paginate(100);
        return StaticPageResource::collection($static_page)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $static_page = StaticPage::where('uuid', $uuid)->firstOrFail();
        return StaticPageShowResource::make($static_page)->additional(['status' => 200, 'message' => '']);
    }

    public function store(StaticPageRequest $request)
    {
        DB::beginTransaction();
        try {
            $static_page = StaticPage::create($request->validated() + ['admin_id' => auth('admin')->id()]);
            DB::commit();
            return StaticPageResource::make($static_page)->additional(['status' => 200, 'message' => 'StaticPage created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdateStaticPageRequest $request, $id)
    {
        $static_page = StaticPage::findOrFail($id);
        DB::beginTransaction();
        try {
            $static_page->update($request->validated());
            DB::commit();
            return StaticPageResource::make($static_page)->additional(['status' => 200, 'message' => 'StaticPage updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy($id)
    {
        $static_page = StaticPage::findOrFail($id);
        $static_page->delete();
        return response()->json(['message' => 'StaticPage deleted successfully']);
    }
}
