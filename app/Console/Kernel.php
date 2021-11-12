<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Poi::class,
        \App\Console\Commands\Order::class,
        \App\Console\Commands\OrderItems::class,
        \App\Console\Commands\OrderMeasures::class,
        \App\Console\Commands\Products::class,
        \App\Console\Commands\Routes::class,
        \App\Console\Commands\WayPoints::class,
        
        \App\Console\Commands\ConstraintTypes::class,
        \App\Console\Commands\VehiclesGet::class,
        \App\Console\Commands\DriversGet::class,
        \App\Console\Commands\PoisGet::class,
        \App\Console\Commands\ProductsGet::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         //$schedule->command('constraint:type')->everyMinute();
        //  $schedule->command('order:post')->everyMinute();
        //  $schedule->command('poi:post')->everyMinute();
        
        $schedule->command('vehicles:get')->everyMinute()->withoutOverlapping();
        $schedule->command('drivers:get')->everyMinute()->withoutOverlapping();
        $schedule->command('pois:get')->everyMinute()->withoutOverlapping();
        $schedule->command('products:get')->everyMinute()->withoutOverlapping();
        $schedule->command('consolidatedRoutes:get')->everyMinute()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
