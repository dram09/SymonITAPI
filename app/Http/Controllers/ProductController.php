<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\ProductFormRequest;
use App\Models\Product;

class ProductController extends Controller
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
        ])->get('https://saas.quadminds.com/api/v2/products/search?limit=100&offset=0');

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
    public function store(ProductFormRequest $request)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type'=> 'application/json',
             'x-saas-apikey' => 'sWA64H9tSDeaj5OTTTgGWwCddBPmGpC7XX6qeBsr'
        ])->post('https://saas.quadminds.com/api/v2/products',[[
            'code'      => $request->input('code'),
            'description'      => $request->input('description')
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
        ])->get('https://saas.quadminds.com/api/v2/products/'.$id);

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
    public function update(ProductFormRequest $request, $id)
    {
        //
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
