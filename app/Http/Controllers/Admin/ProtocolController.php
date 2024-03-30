<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Protocol};
use App\Http\Requests\Admin\Protocol\ProtocolRequest;
use App\Http\Resources\Admin\Protocol\ProtocolResource;
use App\Services\ElasticsearchService;
use Throwable;

class ProtocolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $protocols = Protocol::latest()->paginate(100);
        return (ProtocolResource::collection($protocols))->additional(['status' => 200, 'message' => 'request done']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProtocolRequest $request)
    {
        //
        $protocol = Protocol::create($request->validated());
        try {
            $elasticsearch_service = new ElasticsearchService();
            $doc_saved = $elasticsearch_service->store_document('protocols', ['id' => $protocol->id, 'title' => $protocol->title, 'desc' => $protocol->desc, 'slug' => $protocol->slug]);
        } catch (Throwable $e) {
        }
        return (new ProtocolResource($protocol))->additional(['status' => 200, 'message' => 'request done']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $protocol = Protocol::where('slug', $slug)->firstOrFail();
        return (new ProtocolResource($protocol))->additional(['status' => 200, 'message' => 'request done']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProtocolRequest $request, string $id)
    {
        $protocol  = Protocol::findOrFail($id);
        $protocol->update($request->validated());
        return (new ProtocolResource($protocol))->additional(['status' => 200, 'message' => 'request done']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Protocol $protocol)
    {
        if ($protocol->delete()) {
            return response()->json(['data' => null, 'message' => 'Protocol deleted successfully', 'status' => 200], 200);
        }
        return response()->json(['data' => null, 'message' => 'please contact support', 'status' => 200], 200);
    }
}
