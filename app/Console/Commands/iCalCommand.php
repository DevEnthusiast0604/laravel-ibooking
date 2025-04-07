<?php

namespace App\Console\Commands;

use App\Plugins\ICal\Controllers\ICalController;
use Illuminate\Console\Command;

class iCalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ical:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
     * @return mixed
     */
    public function handle()
    {
        $info = ICalController::inst()->importIcal();
        $this->info($info);
    }
}
