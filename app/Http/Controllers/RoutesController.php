<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\RouteFormRequest;
use App\Models\Route;

class RoutesController extends Controller
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
        ])->get('https://saas.quadminds.com/api/v2/routes/search?limit=100&offset=0&from=2021-01-01&to=2021-01-06');

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
    public function store(RouteFormRequest $request)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type'=> 'application/json',
             'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->post('https://saas.quadminds.com/api/v2/routes',[[
            'code'                  => $request->input('code'),
            'starDate'              => $request->input('starDate'),
            'date'                  => $request->input('date'),
            'deviceId'              => (float)$request->input('deviceId'),
            'driverId'              => (float)$request->input('driverId'),
            'secondaryCode'         => $request->input('secondaryCode'),
            'warehouseDepartureDate'=> $request->input('warehouseDepartureDate'),
            'warehouseReturnDate'   => $request->input('warehouseReturnDate'),
            'carrier'               => $request->input('carrier'),
            'scheduledStartDate'    => $request->input('scheduledStartDate'),
            'status'                => $request->input('status')
        ]]);

        return response()->json($response->json(), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->get('https://saas.quadminds.com/api/v2/routes/'.$id);

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
    public function update(RouteFormRequest $request, $id)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type'=> 'application/json',
             'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->patch('https://saas.quadminds.com/api/v2/routes/'.$id,[
            'starDate'              => $request->input('starDate'),
            'date'                  => $request->input('date'),
            'deviceId'              => (float)$request->input('deviceId'),
            'driverId'              => (float)$request->input('driverId'),
            'secondaryCode'         => $request->input('secondaryCode'),
            'warehouseDepartureDate'=> $request->input('warehouseDepartureDate'),
            'warehouseReturnDate'   => $request->input('warehouseReturnDate'),
            'carrier'               => $request->input('carrier'),
            'scheduledStartDate'    => $request->input('scheduledStartDate'),
            'status'                => $request->input('status')
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
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type'=> 'application/json',
             'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->delete('https://saas.quadminds.com/api/v2/routes/'.$id);

        return response()->json($response->json(), 200);
    }
}
