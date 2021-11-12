<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DriversController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DriversGet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drivers:get';

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
        $drivers = new DriversController();
        $page = 0;
        do{
            $result = $drivers->index($page++);
            $bContinue = false;
            if($result->status() == 200)
            {
                $idCompania = DB::table('compania')->first()->id;
                $idSucursal = DB::table('sucursal')->where('id_compania',$idCompania)->first()->id;
                // Log::info($result);
                foreach($result->original['data'] as  $driver)
                {
                    if (DB::table('vendedor')->where('qm_id', $driver['_id'])->exists()) 
                    {
                        DB::table('vendedor')->where('qm_id', $driver['_id'])->update([
                            'id_compania' => $idCompania,
                            'id_sucursal' => $idSucursal,
                            'nombre' => $driver['name'],
                            'imei_movil' => $driver['extras']['assignedPhone'],
                            'pin' => $driver['_id'],
                            'ptn' => $driver['_id']
                        ]);
                    }
                    else
                    {
                        DB::table('vendedor')->insert([
                            'qm_id' => $driver['_id'],
                            'id_compania' => $idCompania,
                            'id_sucursal' => $idSucursal,
                            'nombre' => $driver['name'],
                            'imei_movil' => $driver['extras']['assignedPhone'],
                            'pin' => $driver['_id'],
                            'ptn' => $driver['_id']
                        ]);
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
