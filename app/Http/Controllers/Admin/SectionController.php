<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Book,Section};
use App\Http\Requests\Admin\Section\{SectionRequest, UpdateSectionRequest};
use App\Http\Resources\Admin\Section\SectionResource;
use App\Services\ElasticsearchService;
use Throwable;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::paginate(100);
        return (SectionResource::collection($sections))->additional(['status'=>200,'message'=>'request done']);
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
    public function store(SectionRequest $request)
    {
        //
        $section = Section::create($request->validated());
        try{
            $elasticsearch_service = new ElasticsearchService();
            $doc_saved = $elasticsearch_service->store_document('sections', ['id' => $section->id, 'title' => $section->title, 'desc' => $section->desc, 'slug' => $section->slug]);
        }catch(Throwable $e)
        {}
        return (new SectionResource($section))->additional(['status'=>200,'message'=>'request done']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $section = Section::where('slug',$slug)->firstOrFail();
        return (new SectionResource($section))->additional(['status'=>200,'message'=>'request done']);

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
    public function update(UpdateSectionRequest $request, string $id)
    {
        $section  = Section::findOrFail($id);
        $section->update($request->validated());
        return (new SectionResource($section))->additional(['status'=>200,'message'=>'request done']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        if($section->delete()){
            return response()->json(['data'=>null,'message'=>'Section deleted successfully','status'=>200],200);
        }
        return response()->json(['data'=>null,'message'=>'please contact support','status'=>200],200);

    }
}
