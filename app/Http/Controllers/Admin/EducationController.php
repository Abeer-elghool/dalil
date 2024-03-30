<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Education;
use App\Http\Requests\Admin\Education\EducationRequest;
use App\Http\Resources\Admin\Education\EducationResource;
class EducationController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $education = Education::latest()->paginate(100);
        return (EducationResource::collection($education))->additional(['status'=>200,'message'=>'request done']);
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
    public function store(EducationRequest $request)
    {
        //
        $education = Education::create($request->validated());
        return (new EducationResource($education))->additional(['status'=>200,'message'=>'request done']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $education = Education::where('slug',$slug)->firstOrFail();
        return (new EducationResource($education))->additional(['status'=>200,'message'=>'request done']);
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
    public function update(EducationRequest $request, string $id)
    {
        $education = Education::findOrFail($id);
        $education->update($request->validated());
        return (new EducationResource($education))->additional(['status'=>200,'message'=>'request done']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Education $education)
    {

        if($education->delete()){
            return response()->json(['data'=>null,'message'=>'Education deleted successfully','status'=>200],200);
        }
        return response()->json(['data'=>null,'message'=>'please contact support','status'=>200],200);
    }
}
