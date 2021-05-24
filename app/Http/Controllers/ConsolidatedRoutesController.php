<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\RouteFormRequest;
use App\Http\Requests\ConsolidatedRoutesRequest;
use App\Models\Route;
use Illuminate\Support\Facades\Log;

class ConsolidatedRoutesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ConsolidatedRoutesRequest $request)
    {
        $aParams = [];
        foreach($request->all() as $key => $value){
            $aParams[] = $key.'='.$value;
        }
        $sParams = implode('&',$aParams);
        Log::info($sParams);
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->get('https://saas.quadminds.com/api/v2/consolidated-routes/search?'.$sParams);

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
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}
