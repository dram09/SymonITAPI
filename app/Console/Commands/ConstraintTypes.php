<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ConstraintTypesController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConstraintTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'constraint:type';

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

        $constraint = new ConstraintTypesController();

        $result = $constraint->index();

        foreach($result->original['data'] as  $type)
        {
            if (DB::table('qm_constraint_types')->where('qm_id', $type['_id'])->doesntExist()) {

                DB::table('qm_constraint_types')->insert([
                    'qm_id'         =>  $type['_id'],
                    'qm_name'       =>  $type['name'],
                    'qm_unitId'     =>  $type['unitId'],
                    'qm_iconUrl'    =>  $type['iconUrl']
                ]);
            }

        }

    }
}
