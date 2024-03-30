<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Http\Requests\Admin\Country\CountryRequest;
use App\Http\Resources\Admin\Country\CountryResource;
class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $countries = Country::latest()->paginate(100);
        return (CountryResource::collection($countries))->additional(['status'=>200,'message'=>'request done']);
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
    public function store(CountryRequest $request)
    {
        //
        $country = Country::create($request->validated());
        return (new CountryResource($country))->additional(['status'=>200,'message'=>'request done']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $country = Country::where('slug',$slug)->firstOrFail();
        return (new CountryResource($country))->additional(['status'=>200,'message'=>'request done']);
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
    public function update(CountryRequest $request, string $id)
    {
        $country = Country::findOrFail($id);
        $country->update($request->validated());
        return (new CountryResource($country))->additional(['status'=>200,'message'=>'request done']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {

        if($country->delete()){
            return response()->json(['data'=>null,'message'=>'Country deleted successfully','status'=>200],200);
        }
        return response()->json(['data'=>null,'message'=>'please contact support','status'=>200],200);
    }
}
