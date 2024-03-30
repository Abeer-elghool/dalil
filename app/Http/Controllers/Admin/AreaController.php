<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Area,City};
use App\Http\Requests\Admin\Area\AreaRequest;
use App\Http\Resources\Admin\Area\AreaResource;
class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $areas = Area::latest()->paginate(100);
        return (AreaResource::collection($areas))->additional(['status'=>200,'message'=>'request done']);
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
    public function store(AreaRequest $request)
    {
        //
        $city = City::find($request->city_id);
        $area = Area::create($request->validated()+['country_id'=>$city->country_id]);
        return (new AreaResource($area))->additional(['status'=>200,'message'=>'request done']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $area = Area::where('slug',$slug)->firstOrFail();
        return (new AreaResource($area))->additional(['status'=>200,'message'=>'request done']);
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
    public function update(AreaRequest $request, string $id)
    {
        $city = City::find($request->city_id);
        $area  = Area::findOrFail($id);
        $area->update($request->validated()+['country_id'=>$city->country_id]);
        return (new AreaResource($area))->additional(['status'=>200,'message'=>'request done']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {

        if($area->delete()){
            return response()->json(['data'=>null,'message'=>'Area deleted successfully','status'=>200],200);
        }
        return response()->json(['data'=>null,'message'=>'please contact support','status'=>200],200);

    }
}
