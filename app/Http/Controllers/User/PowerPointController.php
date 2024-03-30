<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\PowerPoint\PowerPointResource;
use App\Models\PowerPoint;
use App\Services\ViewService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PowerPointController extends Controller
{
    function power_points() : AnonymousResourceCollection {
        return PowerPointResource::collection(PowerPoint::latest()->get())->additional(['status' => 200, 'message' => '']);
    }

    function power_point_by_slug($slug) : PowerPointResource {
        $power_point = PowerPoint::where('slug', $slug)->firstOrFail();
        ViewService::view($power_point->id, $power_point, 'App\\Models\\PowerPoint');
        return PowerPointResource::make($power_point)->additional(['status' => 200, 'message' => '']);
    }
}
