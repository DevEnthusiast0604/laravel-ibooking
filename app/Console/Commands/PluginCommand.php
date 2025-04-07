<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PluginCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:link';

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
        $folders = glob(app_path('Plugins/*'), GLOB_ONLYDIR);
        if(!empty($folders)) {
            foreach ($folders as $k => $folder) {
                $plugin = basename($folder);
                $check = true;
                if (is_dir(public_path('plugins/' . $plugin))) {
                    $check = false;
                    $this->error('The "public/plugins/' . $plugin . '" directory already exists.');
                }
                if($check) {
                    if(is_dir(app_path('Plugins/' . $plugin . '/Assets'))) {
                        $this->laravel->make('files')->link(
                            app_path('Plugins/' . $plugin . '/Assets'), public_path('plugins/' . $plugin)
                        );
                        $this->info('The [public/plugins/' . $plugin . '] directory has been linked.');
                    }
                }
            }
        }
    }
}
