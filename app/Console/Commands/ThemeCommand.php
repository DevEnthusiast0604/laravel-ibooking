<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ThemeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:link';

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
        $folders = glob(app_path('Themes/*'), GLOB_ONLYDIR);
        if(!empty($folders)) {
            foreach ($folders as $k => $folder) {
                $theme = strtolower(basename($folder));
                $check = true;
                if (is_dir(public_path('themes/' . $theme))) {
                    $check = false;
                    $this->error('The "public/themes/' . $theme . '" directory already exists.');
                }
                if($check) {
                    $this->laravel->make('files')->link(
                        app_path('Themes/' . ucfirst($theme) . '/Assets'), public_path('themes/' . $theme)
                    );
                    $this->laravel->make('files')->link(
                        app_path('Themes/' . ucfirst($theme) . '/screenshot.png'), public_path('themes/' . $theme . '/screenshot.png')
                    );
                    $this->info('The [public/themes/' . $theme . '] directory has been linked.');
                }
            }
        }
    }
}
