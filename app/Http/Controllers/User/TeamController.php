<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\Team\TeamResource;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TeamController extends Controller
{
    function teams(): AnonymousResourceCollection
    {
        return TeamResource::collection(Team::active()->latest()->get())->additional(['status' => 200, 'message' => '']);
    }

    function team_by_slug($slug): TeamResource
    {
        return TeamResource::make(Team::active()->where('slug', $slug)->firstOrFail())->additional(['status' => 200, 'message' => '']);
    }
}
