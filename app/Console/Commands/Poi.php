<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PoiController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PoiFormRequest;
use Illuminate\Support\Facades\Log;

class Poi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'poi:type';

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
        $pois = DB::table('qm_pois')->get();

        foreach($pois as $poi) {

            $request = new PoiFormRequest();
            $request->request->add(array(
                'code'              => str_pad($poi->code,2,"0",STR_PAD_LEFT),
                'name'              => $poi->name,
                'longitude'         => $poi->longitude,
                'latitude'          => $poi->latitude,
                'enabled'           => $poi->enabled,
                'poiType'           => $poi->poiType,
                'phoneNumber'       => $poi->phoneNumber,
                'visitingFrequency' => $poi->visitingFrequency,
                'visitingDaysDevice1' => $poi->visitingDaysDevice1,
                'timeWindow'        => $poi->timeWindow,
                'longAddress'       => $poi->longAddress,
                // 'cep'               => $poi->cep,
            ));

            $objeto = new PoiController();
            $result = $objeto->store($request);

            if(isset($result->original['data'][0]['_id']))
            {
                DB::table('cliente')
                ->where('id', $poi->code)
                ->update(['qm_id' => $result->original['data'][0]['_id']]);
            }else{
                Log::error($result);
            }

        }


    }
}
