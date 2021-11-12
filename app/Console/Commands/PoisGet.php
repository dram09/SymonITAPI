<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PoiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PoisGet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pois:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consulta a la base de datos en la table cliente';

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
        $poiController = new PoiController();
        $page = 0;
        do{
            $result = $poiController->index($page++);
            $bContinue = false;
            if($result->status() == 200)
            {
                // Log::info($result);
                $idCompania = DB::table('compania')->first()->id;
                $idSucursal = DB::table('sucursal')->where('id_compania',$idCompania)->first()->id;
                foreach($result->original['data'] as  $poi)
                {
                    if (DB::table('cliente')->where('qm_id', $poi['_id'])->exists()) 
                    {
                        DB::table('cliente')->where('qm_id', $poi['_id'])->update([
                            'id_compania' => $idCompania,
                            'id_sucursal' => $idSucursal,
                            'nombre' => trim($poi['name']),
                            'razon_social' => trim($poi['name']),
                            'codigo' => $poi['code'],
                            'direccion' => trim($poi['address']),
                            'telefono' => $poi['phoneNumber'],
                            'email' => $poi['email'],
                            'latitud' => $poi['latitude'],
                            'longitud' => $poi['longitude'],
                        ]);
                    }
                    else
                    {
                        DB::table('cliente')->insert([
                            'qm_id' => $poi['_id'],
                            'id_compania' => $idCompania,
                            'id_sucursal' => $idSucursal,
                            'nombre' => trim($poi['name']),
                            'razon_social' => trim($poi['name']),
                            'codigo' => $poi['code'],
                            'direccion' => trim($poi['address']),
                            'telefono' => $poi['phoneNumber'],
                            'email' => $poi['email'],
                            'latitud' => $poi['latitude'],
                            'longitud' => $poi['longitude'],
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
