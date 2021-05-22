<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PoiController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PoiFormRequest;

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
        $pois = DB::table('pois')->where('quadmin_id', null)->get();

        foreach($pois as $poi) {

            $request = new PoiFormRequest();

            $request->request->add(array(
                'code'              => $poi->code,
                'name'              => $poi->name,
                'longitude'         => $poi->longitude,
                'latitude'          => $poi->latitude,
                'enabled'           => $poi->enabled,
                'poiType'           => $poi->poiType,
                'phoneNumber'       => $poi->phoneNumber,
                'visitingFrequency' => $poi->visitingFrequency,
                'visitingDaysDevice1' => $poi->visitingDaysDevice1,
                'longAddress'       => $poi->longAddress,
                'cep'               => $poi->cep
            ));


            $objeto = new PoiController();
            $result = $objeto->store($request);

            if(isset($result->original['data'][0]['_id']))
            {
                DB::table('pois')
                ->where('id', $poi->id)
                ->update(['quadmin_id' => $result->original['data'][0]['_id']]);
            }

        }


    }
}
