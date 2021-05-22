<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\OrderFormRequest;
use App\Models\Order;

class OrderController extends Controller
{

    public function index()
    {

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->get('https://saas.quadminds.com/api/v2/orders/search?limit=100&offset=0&code=12345678981011&from=2021-05-20&to=2021-05-25');

        return response()->json($response->json(), 200);
    }


    public function create()
    {
        //
    }


    public function store(OrderFormRequest $request)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type'=> 'application/json',
             'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->post('https://saas.quadminds.com/api/v2/orders',[[
            'poiId'     => (int)$request->input('poiId'),
            'code'      => $request->input('code'),
            'date'      => $request->input('date'),
            'operation' => $request->input('operation')
        ]]);

        return response()->json($response->json());
    }

    public function show($id)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->get('https://saas.quadminds.com/api/v2/orders/'.$id);

        return response()->json($response->json(), 200);
    }


    public function edit($id)
    {
        //
    }


    public function update(OrderFormRequest $request, $id)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type'=> 'application/json',
             'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->patch('https://saas.quadminds.com/api/v2/orders/'.$id,[
            'code'      => $request->input('code'),
            'date'      => $request->input('date'),
            'operation' => $request->input('operation')
        ]);

        return response()->json($response->json(), 200);
    }


    public function destroy($id)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type'=> 'application/json',
             'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->delete('https://saas.quadminds.com/api/v2/orders/'.$id);

        return response()->json($response->json(), 200);
    }
}
