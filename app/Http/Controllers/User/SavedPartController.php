<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SavedPart\SavedPartRequest;
use App\Http\Resources\User\SavedResource\SavedPartResource;
use Illuminate\Http\Request;

class SavedPartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $saved_parts = auth('api')->user()->saved_parts()->when($request->lesson_id, function($q) use($request) {
            $q->where('lesson_id', $request->lesson_id);
        })->latest()->paginate(10);
        return SavedPartResource::collection($saved_parts)->additional(['status' => 200, 'messages' => '']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SavedPartRequest $request)
    {
        $saved_part = auth('api')->user()->saved_parts()->create($request->validated());
        return SavedPartResource::make($saved_part)->additional(['status' => 200, 'messages' => 'create success.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        auth('api')->user()->saved_parts()->findOrFail($id)->delete();
        return response()->json(['status' => 200, 'data' => null, 'message' => 'delete success.']);
    }
}
