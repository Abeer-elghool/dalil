<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Endorser\{EndorserRequest, UpdateEndorserRequest};
use App\Http\Resources\Admin\Endorser\{EndorserResource, EndorserShowResource};
use App\Models\Endorser;
use Illuminate\Support\Facades\DB;
use Throwable;

class EndorserController extends Controller
{
    public function index()
    {
        $endorsers = Endorser::latest()->paginate(100);
        return EndorserResource::collection($endorsers)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $endorser = Endorser::where('uuid', $uuid)->firstOrFail();
        return EndorserShowResource::make($endorser)->additional(['status' => 200, 'message' => '']);
    }

    public function store(EndorserRequest $request)
    {
        DB::beginTransaction();
        try {
            $endorser = Endorser::create($request->validated() + ['admin_id' => auth('admin')->id()]);
            DB::commit();
            return EndorserResource::make($endorser)->additional(['status' => 200, 'message' => 'Endorser created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdateEndorserRequest $request, Endorser $endorser)
    {
        DB::beginTransaction();
        try {
            $endorser->update($request->validated());
            DB::commit();
            return EndorserResource::make($endorser)->additional(['status' => 200, 'message' => 'Endorser updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy(Endorser $endorser)
    {
        $endorser->delete();
        return response()->json(['message' => 'Endorser deleted successfully']);
    }
}
