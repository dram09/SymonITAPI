<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\PoiFormRequest;
use App\Models\Poi;

class PoiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->get('https://saas.quadminds.com/api/v2/pois/search?limit=100&offset=0');

        return response()->json($response->json(), 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoiFormRequest $request)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type'=> 'application/json',
             'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->post('https://saas.quadminds.com/api/v2/pois',[[
            "longAddress"               => $request->input('longAddress'),
            "geocodeByCep"              => (object)['cep'=>$request->input('cep')],
            "code"                      => $request->input('code'),
            "name"                      => $request->input('name'),
            "longitude"                 => (float)$request->input('longitude'),
            "latitude"                  => (float)$request->input('latitude'),
            "enabled"                   => (bool)$request->input('enabled'),
            "poiType"                   => $request->input('poiType'),
            "phoneNumber"               => $request->input('phoneNumber'),
            "visitingFrequency"         => $request->input('visitingFrequency'),
            "visitingDaysDevice1"       => [(int)$request->input('visitingDaysDevice1')]
        ]]);

       return response()->json($response->json(), 200);

    }


    public function show($id)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->get('https://saas.quadminds.com/api/v2/pois/'.$id);

        return response()->json($response->json(), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PoiFormRequest $request, $id)
    {

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type'=> 'application/json',
             'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->put('https://saas.quadminds.com/api/v2/pois/'.$id,[
            'longAddress'             => $request->input('longAddress'),
            'geocodeByCep'  => (object)['cep'=>$request->input('cep')],
            'name'                      => $request->input('name'),
            'longitude'                 => (float)$request->input('longitude'),
            'latitude'                  => (float)$request->input('latitude'),
            'enabled'                   => (bool)$request->input('enabled'),
            'poiType'                   => $request->input('poiType'),
            'phoneNumber'               => $request->input('phoneNumber'),
            'visitingFrequency'         => $request->input('visitingFrequency'),
            'visitingDaysDevice1'       => [(int)$request->input('visitingDaysDevice1')]
        ]);

       return response()->json($response->json(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
