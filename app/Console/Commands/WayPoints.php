<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\WayPointsController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\WayPointFormRequest;

class WayPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'way:points';

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

        $way_points = DB::table('way_points')->where('quadmin_id', null)->get();

        foreach($way_points as $way_point) {

            $request = new WayPointFormRequest();

            $request->request->add(array(
                'id'                            => $way_point->id,
                'scheduledDate'                 => $way_point->scheduledDate,
                'poiId'                         => $way_point->poiId,
                'totalValue'                    => $way_point->totalValue,
                'pallets'                       => $way_point->pallets,
                'createAt'                      => $way_point->createAt,
                'scheduledArrivalTimestamp'     => $way_point->scheduledArrivalTimestamp,
                'scheduledDepartureTimestamp'   => $way_point->scheduledDepartureTimestamp,
                'visitOrder'                    => $way_point->visitOrder,
                'activities'                    => $way_point->activities

            ));


            $objeto = new WayPointsController();
            $result = $objeto->store($request);

            if(isset($result->original['data'][0]['_id']))
            {
                DB::table('routes')
                ->where('id', $way_point->id)
                ->update(['quadmin_id' => $result->original['data'][0]['_id']]);
            }

        }
    }
}
