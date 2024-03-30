<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialty;
use App\Http\Requests\Admin\Specialty\SpecialtyRequest;
use App\Http\Resources\Admin\Specialty\SpecialtyResource;
class SpecialtyController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $specialty = Specialty::latest()->paginate(100);
        return (SpecialtyResource::collection($specialty))->additional(['status'=>200,'message'=>'request done']);
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
    public function store(SpecialtyRequest $request)
    {
        //
        $specialty = Specialty::create($request->validated());
        return (new SpecialtyResource($specialty))->additional(['status'=>200,'message'=>'request done']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $specialty = Specialty::where('slug',$slug)->firstOrFail();
        return (new SpecialtyResource($specialty))->additional(['status'=>200,'message'=>'request done']);
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
    public function update(SpecialtyRequest $request, string $id)
    {
        $specialty = Specialty::findOrFail($id);
        $specialty->update($request->validated());
        return (new SpecialtyResource($specialty))->additional(['status'=>200,'message'=>'request done']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialty $specialty)
    {

        if($specialty->delete()){
            return response()->json(['data'=>null,'message'=>'Specialty deleted successfully','status'=>200],200);
        }
        return response()->json(['data'=>null,'message'=>'please contact support','status'=>200],200);
    }
}
