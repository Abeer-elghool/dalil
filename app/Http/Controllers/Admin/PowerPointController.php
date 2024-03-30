<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PowerPoint\{PowerPointRequest, UpdatePowerPointRequest};
use App\Http\Resources\Admin\PowerPoint\{PowerPointResource, PowerPointShowResource};
use App\Models\PowerPoint;
use App\Services\ElasticsearchService;
use Illuminate\Support\Facades\DB;
use Throwable;

class PowerPointController extends Controller
{
    public function index()
    {
        $power_points = PowerPoint::latest()->paginate(100);
        return PowerPointResource::collection($power_points)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $power_point = PowerPoint::where('uuid', $uuid)->firstOrFail();
        return PowerPointShowResource::make($power_point)->additional(['status' => 200, 'message' => '']);
    }

    public function store(PowerPointRequest $request)
    {
        DB::beginTransaction();
        try {
            $power_point = PowerPoint::create($request->validated() + ['admin_id' => auth('admin')->id()]);
            try {
                $elasticsearch_service = new ElasticsearchService();
                $doc_saved = $elasticsearch_service->store_document('power_points', ['id' => $power_point->id, 'title' => $power_point->title, 'desc' => $power_point->desc, 'slug' => $power_point->slug]);
            } catch (Throwable $e) {
            }
            DB::commit();
            return PowerPointResource::make($power_point)->additional(['status' => 200, 'message' => 'PowerPoint created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdatePowerPointRequest $request, PowerPoint $power_point)
    {
        DB::beginTransaction();
        try {
            $power_point->update($request->validated());
            DB::commit();
            return PowerPointResource::make($power_point)->additional(['status' => 200, 'message' => 'PowerPoint updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy(PowerPoint $power_point)
    {
        $power_point->delete();
        return response()->json(['message' => 'PowerPoint deleted successfully']);
    }
}
