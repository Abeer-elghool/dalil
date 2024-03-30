<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProtocolCategory\{ProtocolCategoryRequest, UpdateProtocolCategoryRequest};
use App\Http\Resources\Admin\ProtocolCategory\{ProtocolCategoryResource, ProtocolCategoryShowResource};
use App\Models\ProtocolCategory;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProtocolCategoryController extends Controller
{
    public function index()
    {
        $categories = ProtocolCategory::latest()->paginate(100);
        return ProtocolCategoryResource::collection($categories)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $category = ProtocolCategory::where('uuid', $uuid)->firstOrFail();
        return ProtocolCategoryShowResource::make($category)->additional(['status' => 200, 'message' => '']);
    }

    public function store(ProtocolCategoryRequest $request)
    {
        DB::beginTransaction();
        try {
            $category = ProtocolCategory::create($request->validated() + ['admin_id' => auth('admin')->id()]);
            DB::commit();
            return ProtocolCategoryResource::make($category)->additional(['status' => 200, 'message' => 'category created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            dd($e);
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdateProtocolCategoryRequest $request, ProtocolCategory $protocol_category)
    {
        DB::beginTransaction();
        try {
            $protocol_category->update($request->validated());
            DB::commit();
            return ProtocolCategoryResource::make($protocol_category)->additional(['status' => 200, 'message' => 'category updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy(ProtocolCategory $protocol_category)
    {
        $protocol_category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
