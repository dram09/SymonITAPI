<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\VisitController;
use App\Http\Requests\VisitFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Visit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visit:placed';

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

        $visits = DB::table('guia_cliente_visita')
        ->join('entrega', 'entrega.id_guia_autoventa', '=', 'guia_cliente_visita.id_guia_autoventa','','entrega.id_cliente', '=','guia_cliente_visita.id_cliente')
        ->join('autoventa','autoventa.id_entrega','=','entrega.id')
        ->where('guia_cliente_visita.visited', false)
        ->select('guia_cliente_visita.id','guia_cliente_visita.qm_id','autoventa.longitud','autoventa.latitud','autoventa.fecha')
        ->get();

        foreach($visits as $visit) {

            $request = new VisitFormRequest();

            $request->request->add(array(
                'visited'       => true ,
                'longitude'     => $visit->longitud,
                'latitude'      => $visit->latitud,
                'visitedAt'     => $visit->fecha,
            ));

            $objeto = new VisitController();
            $result = $objeto->update($request,$visit->qm_id);

            if(isset($result->original['data'][0]['_id']))
            {
                DB::table('guia_cliente_visita')
                ->where('id', $visit->id)
                ->update(['visited' => true]);
            }else{
                Log::error($result);
            }

        }
    }
}
