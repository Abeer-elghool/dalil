<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\Endorser\EndorserResource;
use App\Models\Endorser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EndorserController extends Controller
{
    function endorsers(): AnonymousResourceCollection
    {
        return EndorserResource::collection(Endorser::active()->latest()->get())->additional(['status' => 200, 'message' => '']);
    }

    function endorser_by_slug($slug): EndorserResource
    {
        return EndorserResource::make(Endorser::active()->where('slug', $slug)->firstOrFail())->additional(['status' => 200, 'message' => '']);
    }
}
