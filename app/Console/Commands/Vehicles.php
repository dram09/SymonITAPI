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

        foreach($result->original['data'] as $key => $item)
        {
            $code = $item['erpCode'];

            if(!empty($code))
            {
                if (DB::table('vehiculo')->where('codigo_erp', $code)->where('qm_id',null)->exists()) {

                    DB::table('vehiculo')
                    ->where('codigo_erp', $code)
                    ->where('qm_id',null)
                    ->update(['qm_id' => $item['_id']]);

                }

            }else{
                Log::error($code);
            }

        }


    }
}
