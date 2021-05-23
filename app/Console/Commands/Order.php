<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderFormRequest;
use Illuminate\Support\Facades\Log;

class Order extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:post';

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
        $orders = DB::table('qm_orders')->where('qm_id', null)->get();
        //dd($orders);
        foreach($orders as $order) {

            $request = new OrderFormRequest();

            $request->request->add(array(
                'poiId'        => $order->poiId,
                'code'         => $order->code,
                'date'         => $order->date,
                'operation'    => $order->operation,
                'totalAmount'  => $order->totalAmount,
                'orderMeasures' => $order->orderMeasures,
            ));

            $objeto = new OrderController();
            $result = $objeto->store($request);

            if(isset($result->original['data'][0]['_id']))
            {
                DB::table('pedido')
                ->where('id', $order->code)
                ->update(['qm_id' => $result->original['data'][0]['_id']]);
            }else{
                Log::error($result);
            }
        }
    }
}
