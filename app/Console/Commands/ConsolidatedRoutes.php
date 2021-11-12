<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ConsolidatedRoutesController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ConsolidatedRoutesRequest;
// use App\Models\Route;
use Exception;
use Illuminate\Support\Facades\Log;

class Routes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consolidatedRoutes:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $consolidatedRoutesController = new ConsolidatedRoutesController();
        $page = 0;
        do{            
            $consolidatedRoutesForm = new ConsolidatedRoutesRequest();
            $consolidatedRoutesForm->merge(array(
                'limit'                 => 100,
                'offset'                => $page++*100,
                'from'                  => now(),
                'to'                    => now()
            ));
            
            $result = $consolidatedRoutesController->index($consolidatedRoutesForm);
            $bContinue = false;
            if($result->status() == 200)
            {
                // Log::info($result);
                foreach($result->original['data'] as $data){
                    try{

                        // $aOrderId = [];
                        // $aWaypoints = [];
                        // foreach($data['waypoints'] as $waypoint)
                        // {
                        //     $aWaypointOrderId = [];
                        //     foreach($waypoint['activities'][0]['orders'] as $order)
                        //     {
                        //         $aWaypointOrderId[] = $order['_id'];
                        //     }
                        //     // $aOrderId = array_merge($aOrderId,$aWaypointOrderId);
                        //     $aWaypoints[] = [
                        //         $waypoint['_id'],
                        //         $waypoint['poiId'],
                        //         str_replace('T',' ',substr($waypoint['scheduledArrivalTimestamp'], 0,19)),
                        //         $waypoint['visitOrder'],
                        //         implode(',',$aWaypointOrderId)
                        //     ];
                        // }

                        //  DB::beginTransaction();

                        // $insertRoute = DB::select("SELECT qm_insert_route(?,?,?,?,?) AS id",[
                        //     $data['code'],
                        //     $data['startDate'],
                        //     $data['date'],
                        //     $data['deviceId'],
                        //     implode(',',$aOrderId)
                        // ]);
                        // if(!isset($insertRoute[0])){
                        //     Log::error($data);
                        //     Log::error($aOrderId);
                        //     Log::error($insertRoute);
                        // }
                        // $id_guia_autoventa = $insertRoute[0]->id;
                        // foreach($aWaypoints as $oWayPoint)
                        // {
                        //     array_splice($oWayPoint,0,0,$id_guia_autoventa);
                        //     DB::select("CALL qm_insert_waypoint(?,?,?,?,?,?)",$oWayPoint);
                        // }
                        // DB::commit();

                        if(!DB::table('guia_autoventa')->where('codigo',$data['code'])->exists()){
                            

                            DB::beginTransaction();
                            
                            $oVehiculo = DB::table('vehiculo')->where('qm_id',$data['deviceId'])->first();
                            if(is_null($oVehiculo)){
                                throw new Exception('Vehículo no existe', 1000);
                            }

                            $idVendedor = DB::table('vendedor')->where('qm_id',$data['driverId'])->value('id');
                            if(is_null($idVendedor)){
                                throw new Exception('Vendedor no existe', 1001);
                            }
                            
                            $idGuiaAutoventa = DB::table('guia_autoventa')->insertGetId([
                                'id_vehiculo' => $oVehiculo->id,
                                'id_vendedor' => $idVendedor,
                                'id_sucursal' => $oVehiculo->id_sucursal,
                                'id_compania' => $oVehiculo->id_compania,
                                'fecha' => $data['date'],
                                'entrega' => 1,
                                'codigo' => $data['code'],
                                'fecha_inicio' => $data['startDate']
                            ]);
                            
                            foreach($data['waypoints'] as $waypoint)
                            {
                                $idCliente = DB::table('cliente')->where('qm_id', $waypoint['poiId'])->value('id');
                                if(is_null($idCliente)){
                                    throw new Exception('Cliente no existe', 1002);
                                }

                                DB::table('guia_cliente_visita')->insert([
                                    'id_guia_autoventa' => $idGuiaAutoventa,
                                    'id_cliente' => $idCliente,
                                    'qm_id' => $waypoint['_id'],
                                    'fecha_programada_llegada' => str_replace('T',' ',substr($waypoint['scheduledArrivalTimestamp'], 0,19)),
                                    'orden' => $waypoint['visitOrder']
                                ]);

                                foreach($waypoint['activities'] as $activity){
                                    if($activity['type'] == 'delivery'){
                                        foreach($activity['orders'] as $order){

                                            $idEntrega = DB::table('entrega')->insertGetId([
                                                'id_cliente' => $idCliente, 
                                                'id_guia_autoventa' => $idGuiaAutoventa, 
                                                'id_sucursal' => $oVehiculo->id_sucursal, 
                                                'id_compania' => $oVehiculo->id_compania,
                                                'qm_id' => $order['_id'],
                                                'codigo' => $order['code']
                                            ]);

                                            foreach($order['orderItems'] as $orderItem){
                                                $idProducto = DB::table('producto')->where('qm_id', $orderItem['productId'])->value('id');

                                                DB::table('entrega_detalle')->insert([
                                                    'id_entrega' => $idEntrega, 
                                                    'id_producto' => $idProducto, 
                                                    'id_sucursal' => $oVehiculo->id_sucursal, 
                                                    'id_compania' => $oVehiculo->id_compania,
                                                    'cantidad' => $orderItem['quantity'], 
                                                    'cajas' => $orderItem['quantity']
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }

                            // INSERT INTO guia_autoventa_detalle 
                            // (id_guia_autoventa, id_producto, id_sucursal, id_compania, cajas, botellas, total_unidades)
                            // SELECT v_id_guia_autoventa, producto.id, detalle.id_sucursal, detalle.id_compania, 
                            // FLOOR(detalle.total_unidades / producto.unidad_por_caja), 
                            // FLOOR(detalle.total_unidades % producto.unidad_por_caja),
                            // detalle.total_unidades
                            // FROM (
                            //     SELECT id_producto, id_sucursal, id_compania, 
                            //     SUM(total_unidades) AS total_unidades
                            //     FROM detalle_pedido 
                            //     WHERE FIND_IN_SET((SELECT qm_id FROM pedido WHERE id=detalle_pedido.id_pedido LIMIT 1), p_order_id) > 0 
                            //     GROUP BY id_producto
                            // ) AS detalle
                            // JOIN producto ON producto.id=detalle.id_producto;

                            DB::commit();
                        }

                    }
                    catch(Exception $e)
                    {
                        DB::rollBack();
                        Log::error($e->getMessage()."\n".$e->getTraceAsString());
                    }
                }
                $meta = $result->original['meta'];
                if(($meta['offset'] + $meta['limit']) < $meta['total']){
                    $bContinue = true;
                }
            }
            else
            {
                Log::error($result);
            }
        }while($bContinue);
    }

}
