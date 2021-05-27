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

        $pois = DB::table('pois')->where('updated',1)->get();

        foreach($pois as $poi) {

            $request = new PoiFormRequest();
            $request->request->add(array(
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
            ));
            $objeto = new PoiController();
            $result = $objeto->update($request,$poi->code);

            if(isset($result->original['data']['_id']))
            {
                DB::table('pois')
                ->where('id', $poi->id)
                ->update(['updated' => 0]);
            }else{
                Log::error($result);
            }

        }


        // $pois = new PoiController();

        // $result = $pois->index();

        // foreach($result->original['data'] as  $poi)
        // {

        //     DB::table('pois')->upsert([
        //         [
        //          'code'                 => $poi['code'],
        //          'name'                 => $poi['name'],
        //          'longitude'            => $poi['longitude'],
        //          'latitude'             => $poi['latitude'],
        //          'enabled'              => $poi['enabled'],
        //          'poiType'              => $poi['poiType'],
        //          'phoneNumber'          => $poi['phoneNumber'],
        //          'visitingFrequency'    => $poi['visitingFrequency'],
        //          'visitingDaysDevice1'  => isset($poi['visitingDaysDevice1']) ? $poi['visitingDaysDevice1'][0] : 0,
        //          'longAddress'          => $poi['address'],
        //          'quadmin_id'           =>  $poi['_id'],
        //         ],
        //     ], ['code'], ['name','longitude','latitude','enabled','poiType','phoneNumber','visitingFrequency','visitingDaysDevice1','longAddress']);

        // }

    }
}
