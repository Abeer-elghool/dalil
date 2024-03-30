<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Http\Requests\Admin\City\CityRequest;
use App\Http\Resources\Admin\City\CityResource;
class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $cities = City::latest()->paginate(100);
        return (CityResource::collection($cities))->additional(['status'=>200,'message'=>'request done']);
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
    public function store(CityRequest $request)
    {
        //
        $city = City::create($request->validated());
        return (new CityResource($city))->additional(['status'=>200,'message'=>'request done']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $city = City::where('slug',$slug)->firstOrFail();
        return (new CityResource($city))->additional(['status'=>200,'message'=>'request done']);
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
    public function update(CityRequest $request, string $id)
    {
        $city = City::findOrFail($id);
        $city->update($request->validated());
        return (new CityResource($city))->additional(['status'=>200,'message'=>'request done']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {

        if($city->delete()){
            return response()->json(['data'=>null,'message'=>'City deleted successfully','status'=>200],200);
        }
        return response()->json(['data'=>null,'message'=>'please contact support','status'=>200],200);
    }
}
