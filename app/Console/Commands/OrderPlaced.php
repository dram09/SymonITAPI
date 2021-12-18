<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\OrderPlacedController;
use App\Http\Requests\OrderPlacedFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderPlaced extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:placed';

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

        $orders = DB::table('entrega')
        ->join('autoventa', 'autoventa.id_entrega', '=', 'entrega.id')
        ->where('entrega.qm_statusid', null)
        ->select('entrega.id','entrega.statusId','entrega.qm_id')
        ->get();

        foreach($orders as $order) {

            $request = new OrderPlacedFormRequest();

            $request->request->add(array(
                'statusId'  => $order->statusId ,
            ));

            $objeto = new OrderPlacedController();
            $result = $objeto->update($request,$order->qm_id);

            if(isset($result->original['data'][0]['_id']))
            {
                DB::table('entrega')
                ->where('id', $order->id)
                ->update(['qm_statusid' => $order->statusId ]);
            }else{
                Log::error($result);
            }

        }

    }
}
