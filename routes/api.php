<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware'=>'cors'],function(){

    Route::resources([
        'poi'                       =>  'App\Http\Controllers\PoiController',
        'order'                     =>  'App\Http\Controllers\OrderController',
        'order-item'                =>  'App\Http\Controllers\OrderItemsController',
        'order-measure'             =>  'App\Http\Controllers\OrderMeasuresController',
        'product'                   =>  'App\Http\Controllers\ProductController',
        'route'                     =>  'App\Http\Controllers\RoutesController',
        'way-point'                 =>  'App\Http\Controllers\WayPointsController',
    ]);

});
