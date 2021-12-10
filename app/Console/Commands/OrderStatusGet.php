<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\OrderStatusController;
use Illuminate\Support\Facades\DB;

class OrderStatusGet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orderStatus:get';

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

        $constraint = new OrderStatusController();

        $result = $constraint->index();

        foreach($result->original['data'] as  $type)
        {
            if (DB::table('qm_order_status')->where('qm_id', $type['_id'])->exist()) {
                DB::table('qm_order_status')->where('qm_id', $type['_id'])->update([
                    'description'   =>  $type['description'],
                    'status'        =>  $type['status'],
                ]);
            }else{
                DB::table('qm_order_status')->insert([
                    'id'            =>  $type['_id'],
                    'description'   =>  $type['description'],
                    'status'        =>  $type['status'],
                ]);
            }

        }

    }
}
