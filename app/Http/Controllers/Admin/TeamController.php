<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Team\{TeamRequest, UpdateTeamRequest};
use App\Http\Resources\Admin\Team\{TeamResource, TeamShowResource};
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Throwable;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::latest()->paginate(100);
        return TeamResource::collection($teams)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $team = Team::where('uuid', $uuid)->firstOrFail();
        return TeamShowResource::make($team)->additional(['status' => 200, 'message' => '']);
    }

    public function store(TeamRequest $request)
    {
        DB::beginTransaction();
        try {
            $team = Team::create($request->validated() + ['admin_id' => auth('admin')->id()]);
            DB::commit();
            return TeamResource::make($team)->additional(['status' => 200, 'message' => 'Team created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdateTeamRequest $request, Team $team)
    {
        DB::beginTransaction();
        try {
            $team->update($request->validated());
            DB::commit();
            return TeamResource::make($team)->additional(['status' => 200, 'message' => 'Team updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return response()->json(['message' => 'Team deleted successfully']);
    }
}
