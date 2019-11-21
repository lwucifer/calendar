<?php

namespace App\Console;

use App\Console\Commands\RemindPlan;
use App\Console\Commands\UpdatePlanStatus;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use App\Console\URL;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        UpdatePlanStatus::class,
        RemindPlan::class
    ];

    protected $resource = 'App\Http\Resources\PlanResource';

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Automatically update status to done when the day passes
        $schedule->command('plan:status')->everyThirtyMinutes();

        // Automatically send reminder plan mail
        $schedule->command('plan:remind')->everyFiveMinutes();
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
