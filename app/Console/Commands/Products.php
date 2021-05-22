<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductFormRequest;

class Products extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product';

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

        $products = DB::table('products')->where('quadmin_id', null)->get();

        foreach($products as $product) {

            $request = new ProductFormRequest();

            $request->request->add(array(
                'code'              => $product->code,
                'description'       => $product->description,
            ));


            $objeto = new ProductController();
            $result = $objeto->store($request);

            if(isset($result->original['data'][0]['_id']))
            {
                DB::table('products')
                ->where('id', $product->id)
                ->update(['quadmin_id' => $result->original['data'][0]['_id']]);
            }

        }
    }
}
