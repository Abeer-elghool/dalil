<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\{SettingRequest, UpdateSettingRequest};
use App\Http\Resources\Admin\Setting\{SettingResource, SettingShowResource};
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::latest()->paginate(100);
        return SettingResource::collection($setting)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $setting = Setting::where('uuid', $uuid)->firstOrFail();
        return SettingShowResource::make($setting)->additional(['status' => 200, 'message' => '']);
    }

    public function store(SettingRequest $request)
    {
        DB::beginTransaction();
        try {
            $setting = Setting::create($request->validated() + ['admin_id' => auth('admin')->id()]);
            DB::commit();
            return SettingResource::make($setting)->additional(['status' => 200, 'message' => 'Setting created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdateSettingRequest $request, $id)
    {
        $setting = Setting::findOrFail($id);
        DB::beginTransaction();
        try {
            $setting->update($request->validated());
            DB::commit();
            return SettingResource::make($setting)->additional(['status' => 200, 'message' => 'Setting updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();
        return response()->json(['message' => 'Setting deleted successfully']);
    }
}
