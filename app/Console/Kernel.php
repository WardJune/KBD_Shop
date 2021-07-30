<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // * * * * * /opt/lampp/bin/php /run/media/cold/Local\ Disk/Code\ W/xampp/htdocs/kbd/artisan schedule:run >> /dev/null 2>&1
        // $schedule->command('inspire')->hourly();

        // cancel new order
        $schedule->command('order:cancel')->dailyAt('22.59');
        // confirm order
        $schedule->command('order:done')->dailyAt('22.59');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
