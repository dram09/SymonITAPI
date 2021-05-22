<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\OrderMeasuresController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderMeasureFormRequest;

class OrderMeasures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:measures';

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
        $orders = DB::table('order_measures')->where('quadmin_id', null)->get();

        foreach($orders as $order) {

            $request = new OrderMeasureFormRequest();

            $request->request->add(array(
                'id'             => $order->id,
                'constraintId'   => $order->constraintId,
                'value'          => $order->value,
            ));


            $objeto = new OrderMeasuresController();
            $result = $objeto->store($request);

            if(isset($result->original['data'][0]['_id']))
            {
                DB::table('order_measures')
                ->where('id', $order->id)
                ->update(['quadmin_id' => $result->original['data'][0]['_id']]);
            }
        }
    }
}
