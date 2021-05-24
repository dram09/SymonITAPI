<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ConsolidatedRoutesController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ConsolidatedRoutesRequest;
use App\Models\Route;
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
        // $routes = DB::table('routes')->where('quadmin_id', null)->get();
        $consolidatedRoutesForm = new ConsolidatedRoutesRequest();
        $consolidatedRoutesForm->merge(array(
            'limit'                 => 100,
            'offset'                => 0,
            'from'                  => now()->subDays(2),
            'to'                    => now()
        ));

        $consolidatedRoutesController = new ConsolidatedRoutesController();
        $result = $consolidatedRoutesController->index($consolidatedRoutesForm);
        
        if($result->status() == 200)
        {
            Log::info($result);
            foreach($result->original['data'] as $data){
                try{
                    $aOrderId = [];
                    $aWaypoints = [];
                    foreach($data['waypoints'] as $waypoint)
                    {
                        $aWaypointOrderId = [];
                        foreach($waypoint['activities'][0]['orders'] as $order)
                        {
                            $aWaypointOrderId[] = $order['_id'];
                        }
                        $aOrderId = array_merge($aOrderId,$aWaypointOrderId);
                        $aWaypoints[] = [
                            $waypoint['_id'],
                            $waypoint['poiId'],
                            str_replace('T',' ',substr($waypoint['scheduledArrivalTimestamp'], 0,19)),
                            // str_replace('T',' ',substr($waypoint['scheduledDepartureTimestamp'], 0,19)),
                            $waypoint['visitOrder'],
                            implode(',',$aWaypointOrderId)
                        ];
                    }

                    DB::beginTransaction();

                    $insertRoute = DB::select("SELECT qm_insert_route(?,?,?,?,?) AS id",[
                        $data['code'],
                        $data['startDate'],
                        $data['date'],
                        $data['deviceId'],
                        implode(',',$aOrderId)
                    ]);
                    $id_guia_autoventa = $insertRoute[0]->id;
                    foreach($aWaypoints as $oWayPoint)
                    {
                        array_splice($oWayPoint,0,0,$id_guia_autoventa);
                        DB::select("CALL qm_insert_waypoint(?,?,?,?,?,?)",$oWayPoint);
                    }

                    DB::commit();
                }
                catch(Exception $e)
                {
                    DB::rollBack();
                    Log::error($e->getMessage()."\n".$e->getTraceAsString());
                }
            }
        }
        else
        {
            Log::error($result);
        }
    }

}
