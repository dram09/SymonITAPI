<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\PoiFormRequest;
use App\Models\Poi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoiFormRequest $request)
    {
        $data = [[
            "longAddress"               => $request->get('longAddress'),
            //"address"                   => (object)[],
            // "geocodeByCep"              => (object)['cep'=>$request->get('cep')],
            "geocodeByCep"              => (object)[],
            "code"                      => (string)$request->get('code'),
            "name"                      => $request->get('name'),
            "longitude"                 => (float)$request->get('longitude'),
            "latitude"                  => (float)$request->get('latitude'),
            "enabled"                   => (bool)$request->get('enabled'),
            "poiType"                   => $request->get('poiType'),
            "phoneNumber"               => $request->get('phoneNumber'),
            "visitingFrequency"         => $request->get('visitingFrequency'),
            // "visitingDaysDevice1"       => [(int)$request->get('visitingDaysDevice1')],
            "visitingDaysDevice1"       => json_decode($request->get('visitingDaysDevice1')),
            "timeWindow"                => json_decode($request->get('timeWindow')),
        ]];

        Log::info($data);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type'=> 'application/json',
             'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->post('https://saas.quadminds.com/api/v2/pois',$data);

        return response()->json($response->json());
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
            'longAddress'               => $request->get('longAddress'),
            'geocodeByCep'              => (object)['cep'=>$request->get('cep')],
            'name'                      => $request->get('name'),
            'longitude'                 => (float)$request->get('longitude'),
            'latitude'                  => (float)$request->get('latitude'),
            'enabled'                   => (bool)$request->get('enabled'),
            'poiType'                   => $request->get('poiType'),
            'phoneNumber'               => $request->get('phoneNumber'),
            'visitingFrequency'         => $request->get('visitingFrequency'),
            'visitingDaysDevice1'       => [(int)$request->get('visitingDaysDevice1')]
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
