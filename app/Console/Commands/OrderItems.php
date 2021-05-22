<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\OrderItemsController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderItemFormRequest;

class OrderItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:items';

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
        $orders = DB::table('order_items')->where('quadmin_id', null)->get();

        foreach($orders as $order) {

            $request = new OrderItemFormRequest();

            $request->request->add(array(
                'id'          => $order->id,
                'productId'   => $order->productId,
                'quantity'    => $order->quantity,
            ));


            $objeto = new OrderItemsController();
            $result = $objeto->store($request);

            if(isset($result->original['data'][0]['_id']))
            {
                DB::table('order_items')
                ->where('id', $order->id)
                ->update(['quadmin_id' => $result->original['data'][0]['_id']]);
            }
        }
    }
}
