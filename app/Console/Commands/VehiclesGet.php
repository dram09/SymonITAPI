<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\VehiclesController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehiclesGet extends Command
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
        $page = 0;
        do{
            $result = $vehicles->index($page++);
            $bContinue = false;
            if($result->status() == 200)
            {
                // Log::info($result);
                $idCompania = DB::table('compania')->first()->id;
                $idSucursal = DB::table('sucursal')->where('id_compania',$idCompania)->first()->id;
                foreach($result->original['data'] as  $vehicle)
                {
                    if($vehicle['type'] == 'AVL-TRAX'){
                        if (DB::table('vehiculo')->where('qm_id', $vehicle['_id'])->exists()) 
                        {
                            DB::table('vehiculo')->where('qm_id', $vehicle['_id'])->update([
                                'id_compania' => $idCompania,
                                'id_sucursal' => $idSucursal,
                                'nombre' => $vehicle['name'],
                                'codigo_erp' => $vehicle['erpCode'] == '' ? null : $vehicle['erpCode'],
                                'placa' => $vehicle['licensePlate']
                            ]);
                        }
                        else
                        {
                            DB::table('vehiculo')->insert([
                                'qm_id' => $vehicle['_id'],
                                'id_compania' => $idCompania,
                                'id_sucursal' => $idSucursal,
                                'nombre' => $vehicle['name'],
                                'codigo_erp' => $vehicle['erpCode'] == '' ? null : $vehicle['erpCode'],
                                'placa' => $vehicle['licensePlate'],
                                'activo' => 0
                            ]);
                        }
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
