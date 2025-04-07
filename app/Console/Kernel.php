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
        Commands\iCalCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $this->iCalSchedule($schedule);
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

    private function iCalSchedule($schedule)
    {
        $iCalType = env('ICAL_TYPE', 'hour');
        $iCalValue = (int)env('ICAL_VALUE', 0);
        if ($iCalType == 'hour') {
            if ($iCalValue > 1) {
                $schedule->command('ical:run')->cron('0 0/' . $iCalValue . ' * * *');
            } else {
                $schedule->command('ical:run')->cron('0 * * * *');
            }
        } else {
            if ($iCalValue > 1) {
                $schedule->command('ical:run')->cron('*/' . $iCalValue . ' * * * *');
            } else {
                $schedule->command('ical:run')->cron('* * * * *');
            }
        }
    }
}
