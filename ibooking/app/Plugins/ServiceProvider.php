<?php
namespace App\Plugins;
if ( ! defined( 'GMZPATH' ) ) { exit; }

use File;

class ServiceProvider extends  \Illuminate\Support\ServiceProvider{
	public function boot(){
	    $actived = get_actived_plugins();
		$listPlugins = array_map('basename', File::directories(__DIR__));
        foreach ($listPlugins as $plugin) {
            $pluginSlug = strtolower($plugin);
            if(!in_array($pluginSlug, $actived)){
                continue;
            }
            $folderMigration = __DIR__.'/'.$plugin.'/Migrations/';
            if(is_dir($folderMigration)){
                $this->loadMigrationsFrom($folderMigration);
            }

            $configs = glob(__DIR__.'/'.$plugin.'/Config/*');
            if(!empty($configs)) {
                foreach ( $configs as $item ) {
                    if ( file_exists( $item ) ) {
                        $files = explode('.', basename($item));
                        $this->mergeConfigFrom($item, $files[0]);
                    }
                }
            }

            $helpers = glob(__DIR__.'/'.$plugin.'/Helpers/*');
            if(!empty($helpers)) {
                foreach ( $helpers as $item ) {
                    if ( file_exists( $item ) ) {
                        include $item;
                    }
                }
            }

            $libs = glob(__DIR__.'/'.$plugin.'/Libs/*');

            if(!empty($libs)) {
                foreach ( $libs as $item ) {
                    if ( file_exists( $item ) ) {
                        include $item;
                    }
                }
            }


            $controllers = glob(__DIR__ . '/' . $plugin . '/Controllers/*');
            if (!empty($controllers)) {
                foreach ($controllers as $item) {
                    $baseName = explode('.', basename($item));
                    $class = 'App\\Plugins\\'. $plugin .'\\Controllers\\' . $baseName[0];
                    if(method_exists($class, 'inst')) {
                        $class::inst();
                    }
                }
            }


            if(file_exists(__DIR__.'/'.$plugin.'/Routes/web.php')) {
                $this->loadRoutesFrom(__DIR__ . '/' . $plugin . '/Routes/web.php');
            }

			if(is_dir(__DIR__.'/'.$plugin.'/Views')) {
				$this->loadViewsFrom(__DIR__.'/'.$plugin.'/Views', 'Plugin.' . $plugin);
			}
		}
	}
	public function register(){}
}