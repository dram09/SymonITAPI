<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\VehiclesController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Vehicles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicles:get';

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
        $vehicles = new VehiclesController();

        $result = $vehicles->index();

        if($result->status() == 200)
        {
            Log::info($result);
            foreach($result->original['data'] as  $vehicle)
            {
                // if(strlen($vehicle['_id']) >= 15){
                //     continue;
                // }
                //$oVehiculo = ->first();
                if (DB::table('vehiculo')->where('qm_id', $vehicle['_id'])->exists()) 
                {
                    DB::table('vehiculo')->where('qm_id', $vehicle['_id'])->update([
                        'nombre' => $vehicle['name'],
                        'codigo_erp' => $vehicle['erpCode'],
                        'placa' => $vehicle['licensePlate']
                    ]);
                }
                else
                {
                    DB::table('vehiculo')->insert([
                        'qm_id' => $vehicle['_id'],
                        'nombre' => $vehicle['name'],
                        'codigo_erp' => $vehicle['erpCode'],
                        'placa' => $vehicle['licensePlate']
                    ]);
                    
                }

                /*if(!empty($code))
                {
                    if (DB::table('vehiculo')->where('codigo_erp', $code)->where('qm_id',$vehicle)->exists()) {

                        DB::table('vehiculo')
                        ->where('codigo_erp', $code)
                        ->where('qm_id',null)
                        ->update(['qm_id' => $vehicle['_id']]);

                    }
                }else{
                    Log::error($code);
                }*/

            }
        }
        else
        {
            Log::error($result);
        }
    }
}
