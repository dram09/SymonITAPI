<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PoiController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PoiFormRequest;
use App\Models\Poi as ModelsPoi;
use Illuminate\Support\Facades\Log;

class Poi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'poi:post';

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
        $pois = DB::table('qm_pois')->whereNull('qm_id')->get();

        foreach($pois as $poi) {

            $request = new PoiFormRequest();
            $request->request->add([
                'code'              => $poi->code,
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
                'cep'               => $poi->cep,
            ]);

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

        $pois = ModelsPoi::where('updated','>',ModelsPoi::lastUpdated())->orderBy('updated','desc')->get();
        if(count($pois) > 0)
        {
            $fecha = null;
            foreach($pois as $poi) 
            {
                if(is_null($fecha))
                {
                    $fecha = $poi->updated;
                }
                $request = new PoiFormRequest();
                $request->request->add([
                    'code'                  => $poi->code,
                    'name'                  => $poi->name,
                    'longitude'             => $poi->longitude,
                    'latitude'              => $poi->latitude,
                    'enabled'               => $poi->enabled,
                    'poiType'               => $poi->poiType,
                    'phoneNumber'           => $poi->phoneNumber,
                    'visitingFrequency'     => $poi->visitingFrequency,
                    'visitingDaysDevice1'   => $poi->visitingDaysDevice1,
                    'longAddress'           => $poi->longAddress,
                    'cep'                   => $poi->cep
                ]);
                $objeto = new PoiController();
                $result = $objeto->update($request,$poi->code);

                if(!$result && $result->status() != 200)
                {
                    Log::error($result);
                }
            }
            if(!is_null($fecha))
            {
                ModelsPoi::setUpdated($fecha);
            }
        }
    }
}
