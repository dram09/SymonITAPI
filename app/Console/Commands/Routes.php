<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\RoutesController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RouteFormRequest;

class Routes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routes';

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
        $routes = DB::table('routes')->where('quadmin_id', null)->get();

        foreach($routes as $route) {

            $request = new RouteFormRequest();

            $request->request->add(array(
                'code'                  => $route->code,
                'starDate'              => $route->starDate,
                'date'                  => $route->date,
                'deviceId'              => $route->deviceId,
                'driverId'              => $route->driverId,
                'secondaryCode'         => $route->secondaryCode,
                'warehouseReturnDate'   => $route->warehouseReturnDate,
                'carrier'               => $route->carrier,
                'scheduledStartDate'    => $route->scheduledStartDate,
                'status'                => $route->status
            ));


            $objeto = new RoutesController();
            $result = $objeto->store($request);

            if(isset($result->original['data'][0]['_id']))
            {
                DB::table('routes')
                ->where('id', $route->id)
                ->update(['quadmin_id' => $result->original['data'][0]['_id']]);
            }

        }
    }
}
