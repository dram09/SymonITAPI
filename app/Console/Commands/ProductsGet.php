<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductsGet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consulta a la base de datos en la table producto';

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
        $productController = new ProductController();
        $page = 0;
        do{
            $result = $productController->index($page++);
            $bContinue = false;
            if($result->status() == 200)
            {
                // Log::info($result);
                $idCompania = DB::table('compania')->first()->id;
                $idSucursal = DB::table('sucursal')->where('id_compania',$idCompania)->first()->id;
                foreach($result->original['data'] as  $poi)
                {
                    if(is_numeric($poi['code'])){
                        DB::beginTransaction();

                        $idProducto = DB::table('producto')->where('qm_id', $poi['_id'])->value('id');
                        if (!is_null($idProducto)) 
                        {
                            DB::table('producto')->where('id', $idProducto)->update([
                                'id_compania' => $idCompania,
                                'nombre' => trim($poi['description']),
                                'codigo' => $poi['code'],
                            ]);
                        }
                        else
                        {
                            $idProducto = DB::table('producto')->insertGetId([
                                'qm_id' => $poi['_id'],
                                'id_compania' => $idCompania,
                                'nombre' => trim($poi['description']),
                                'codigo' => $poi['code'],
                            ]);
                        }
                        if(!DB::table('producto_sucursal')->where('id_producto', $idProducto)->where('id_sucursal', $idSucursal)->exists()){
                            DB::table('producto_sucursal')->insert([
                                'id_producto' => $idProducto,
                                'id_sucursal' => $idSucursal,
                                'id_compania' => $idCompania
                            ]);
                        }
                        DB::commit();
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
