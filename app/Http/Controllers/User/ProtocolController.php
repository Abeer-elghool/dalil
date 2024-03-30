<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Protocol;
use App\Http\Resources\User\Protocol\ProtocolResource;
use App\Http\Resources\User\ProtocolCategory\ProtocolCategoryResource;
use App\Models\ProtocolCategory;
use App\Services\ViewService;

class ProtocolController extends Controller
{
    public function protocol_categories(Request $request)
    {
        $protocol_categories = ProtocolCategory::active()->when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->orderBy('list_order','asc')->latest()->get();
        return ProtocolCategoryResource::collection($protocol_categories)->additional(['status' => 200, 'messages' => '']);
    }
    
    public function protocol_category_by_slug($slug)
    {
        $protocol_category = ProtocolCategory::active()->where('slug', $slug)->latest()->firstOrFail();
        return ProtocolCategoryResource::make($protocol_category)->additional(['status' => 200, 'messages' => '']);
    }

    public function protocols_by_category_slug($slug)
    {
        $protocol_category = ProtocolCategory::active()->where('slug', $slug)->latest()->firstOrFail();
        $protocols = Protocol::active()->where('protocol_category_id', $protocol_category->id)->select('id', 'slug', 'title', 'desc', 'file')->latest()->get();
        return response()->json(['status' => 200, 'data' => $protocols, 'messages' => '']);
    }

    public function protocols(Request $request)
    {
        $protocols = Protocol::when($request->search, fn ($q) => $q->where('title', 'like', '%' . $request->search . '%'))->latest()->get();
        return ProtocolResource::collection($protocols)->additional(['status' => 200, 'messages' => '']);
    }

    public function protocol_by_slug($slug)
    {
        $protocol = Protocol::where('slug', $slug)->firstOrFail();
        ViewService::view($protocol->id, $protocol, 'App\\Models\\Protocol');
        return ProtocolResource::make($protocol)->additional(['status' => 200, 'messages' => '']);
    }
}
